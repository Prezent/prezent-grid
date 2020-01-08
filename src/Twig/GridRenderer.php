<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\ElementView;
use Prezent\Grid\GridView;
use Prezent\Grid\View;
use Twig\Environment;
use Twig\Template;

/**
 * Grid renderer
 *
 * @author Sander Marechal
 */
class GridRenderer
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * @var array
     */
    private $defaultThemes;

    /**
     * @var \SplObjectStorage Grid themes, indexed by view
     */
    private $themes;

    /**
     * @var \SplObjectStorage Variable stacks, indexed by [view][item]
     */
    private $variableStack;

    /**
     * @var \SplObjectStorage
     */
    private $blocks = [];

    /**
     * Constructor
     *
     * @param array $themes default themes, either as string or as \Twig\Template
     */
    public function __construct(array $themes = [])
    {
        $this->defaultThemes = $themes ?: [dirname(__DIR__) . '/Resources/views/Grid/grid.html.twig'];
        $this->themes = new \SplObjectStorage();
        $this->variableStack = new \SplObjectStorage();
        $this->blocks = new \SplObjectStorage();
    }

    /**
     * Setter for environment
     *
     * @param Environment $environment
     * @return self
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;

        foreach ($this->defaultThemes as &$theme) {
            if (!($theme instanceof Template)) {
                $theme = $this->environment->load($theme)->unwrap();
            }

            $this->loadBlocks($theme);
        }
    }

    /**
     * Set themes for a view
     *
     * @param View $view
     * @param array|\Traversable $themes
     * @return void
     */
    public function setTheme(View $view, $themes)
    {
        $this->themes[$view] = [];

        foreach ($themes as $theme) {
            if (!($theme instanceof Template)) {
                $theme = $this->environment->load($theme)->unwrap();
            }

            $this->loadBlocks($theme);
            $this->themes[$view] = array_merge($this->themes[$view], [$theme]);
        }
    }

    /**
     * Render a block
     *
     * @param string $name
     * @param GridView|ElementView $view
     * @param mixed $item
     * @param array $variables
     * @return string
     */
    public function renderBlock($name, View $view, $item = null, array $variables = [])
    {
        // Environment stacking
        //
        // Every nested call to renderBlock adds the variables to the
        // twig rendering environment for that block
        $variableStack = $this->getVariableStack($view, $item);
        $variables = array_merge($variableStack->top(), $variables);
        $variableStack->push($variables);

        // Widget inheritance
        //
        // Widgets blocks can be overridden on a per-type basis. If a block for a subtype exists
        // then that block will be rendered, else it's parent block type and so forth. Block names
        // are in the format `grid_[type]_widget`. Example:
        //
        // 1) grid_string_widget
        // 2) grid_column_widget
        // 3) grid_widget
        //
        // or
        //
        // 1) grid_header_datetime_widget
        // 2) grid_header_column_widget
        // 3) grid_header_widget
        //
        // This only applies for column types that extend `column`.
        $blockSuffix = strrchr($name, '_');
        $blockPrefix = strlen($blockSuffix) ? substr($name, 0, -strlen($blockSuffix)) : $name;
        $theme = null;

        if ('_widget' == $blockSuffix && $view instanceof ElementView && isset($view->vars['block_types'])) {
            foreach ($view->vars['block_types'] as $blockType) {
                $blockName = $blockPrefix . '_' . $blockType . $blockSuffix;

                if ($theme = $this->findThemeForBlock($blockName, $view)) {
                    $name = $blockName;
                    break;
                }
            }
        } else {
            $theme = $this->findThemeForBlock($name, $view);
        }

        $template = $theme ?: reset($this->defaultThemes);

        // Render the block
        $output = $template->renderBlock($name, $variables);
        $variableStack->pop();

        return $output;
    }

    /**
     * Find the template where a block is defined
     *
     * @param string $name
     * @param View $view
     * @return Template|null
     */
    private function findThemeForBlock($name, View $view)
    {
        // Search themes for current view
        if (isset($this->themes[$view])) {
            foreach ($this->themes[$view] as $theme) {
                if (in_array($name, $this->blocks[$theme])) {
                    return $theme;
                }
            }
        }

        // Search themes for parent view
        if ($view instanceof ElementView && $view->parent && $theme = $this->findThemeForBlock($name, $view->parent)) {
            return $theme;
        }

        // Search default themes
        foreach ($this->defaultThemes as $theme) {
            if (in_array($name, $this->blocks[$theme])) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * Get a variableStack
     *
     * The internal variableStack is organised like this:
     *
     * \SplObjectStorage $this->variableStack [
     *     $view => \SplStack [
     *         'item' => $item,
     *         'stack' => \SplStack [
     *             0 => Bound view variables
     *             1 => 1st renderBlock variables
     *             2 => 2nd nested renderBlock variables
     *             ...
     *         ],
     *     ],
     *     ...
     * ]
     *
     * @param GridView|ElementView $view
     * @param mixed $item
     * @return \SplStack
     */
    private function getVariableStack(View $view, $item)
    {
        if (!$this->variableStack->contains($view)) {
            $this->variableStack->attach($view, new \SplStack());
        }

        foreach ($this->variableStack[$view] as $stackEntry) {
            if ($stackEntry['item'] === $item) {
                return $stackEntry['stack'];
            }
        }

        $variables = [];

        if ($view instanceof GridView) {
            $variables = array_merge(['grid' => $view, 'data' => $item], $view->vars);
        }

        if ($view instanceof ElementView) {
            if ($item) {
                $boundView = clone $view;
                $boundView->bind($item);
                $variables = $boundView->vars;
            } else {
                $variables = $view->vars;
            }

            $variables = array_merge(['column' => $view, 'item' => $item], $variables);
        }

        $stack = new \SplStack();
        $stack->push($this->environment->mergeGlobals($variables));

        $this->variableStack[$view]->push([
            'item' => $item,
            'stack' => $stack,
        ]);

        return $stack;
    }

    /**
     * Load all blocks from the current theme
     *
     * @return void
     */
    private function loadBlocks(Template $theme)
    {
        if (isset($this->blocks[$theme])) {
            return;
        }

        $this->blocks[$theme] = array_keys($theme->getBlocks());

        if ($parent = $theme->getParent($this->environment->mergeGlobals([]))) {
            $this->loadBlocks($parent);
            $this->blocks[$theme] = array_merge($this->blocks[$theme], $this->blocks[$parent]);
        }
    }
}
