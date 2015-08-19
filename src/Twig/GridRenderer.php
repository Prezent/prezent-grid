<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\ColumnView;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\GridView;
use Prezent\Grid\View;

/**
 * Grid renderer
 *
 * @author Sander Marechal
 */
class GridRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var string|\Twig_Template
     */
    private $theme;

    /**
     * @var \SlpObjectStorage Variable stacks, indexed by [view][item]
     */
    private $variableStack;

    /**
     * @var \SplObjectStorage
     */
    private $blocks = [];

    /**
     * Constructor
     *
     * @param string|\Twig_Template $theme
     */
    public function __construct($theme = null)
    {
        $this->theme = $theme ?: dirname(__DIR__) . '/Resources/views/Grid/grid.html.twig';
        $this->variableStack = new \SplObjectStorage();
        $this->blocks = new \SplObjectStorage();
    }

    /**
     * Setter for environment
     *
     * @param \Twig_Environment $environment
     * @return self
     */
    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->environment = $environment;

        if (!($this->theme instanceof \Twig_Template)) {
            $this->theme = $this->environment->loadTemplate($this->theme);
        }

        $this->loadBlocks($this->theme);
    }

    /**
     * Render a block
     *
     * @param string $name
     * @param GridView|ColumnView $view
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

        if ('_widget' == $blockSuffix && $view instanceof ColumnView && isset($view->vars['block_types'])) {
            foreach ($view->vars['block_types'] as $blockType) {
                $blockName = $blockPrefix . '_' . $blockType . $blockSuffix;

                if (in_array($blockName, $this->blocks[$this->theme])) {
                    $name = $blockName;
                    break;
                }
            }
        }

        // Render the block
        $output = $this->theme->renderBlock($name, $variables);
        $variableStack->pop();

        return $output;
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
     * @param GridView|ColumnView $view
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
            $variables = array_merge(['grid' => $view, 'data' => $item]);
        }
        
        if ($view instanceof ColumnView) {
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
        $stack->push($variables);

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
    public function loadBlocks(\Twig_Template $theme)
    {
        $this->blocks[$theme] = array_keys($theme->getBlocks());

        if ($parent = $theme->getParent([])) {
            $this->loadBlocks($parent);
            $this->blocks[$theme] = array_merge($this->blocks[$theme], $this->blocks[$parent]);
        }
    }
}
