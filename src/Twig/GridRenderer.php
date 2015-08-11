<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\ColumnView;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\GridView;

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
     * Constructor
     *
     * @param mixed $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
        $this->variableStack = new \SplObjectStorage();
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

        if (!$this->theme) {
            $this->theme = dirname(__DIR__) . '/Resources/views/Grid/grid.html.twig';
        }

        if (!($this->theme instanceof \Twig_Template)) {
            $this->theme = $this->environment->loadTemplate($this->theme);
        }
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
    public function renderBlock($name, $view, $item = null, array $variables = [])
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

                if ($this->theme->hasBlock($blockName)) {
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
    private function getVariableStack($view, $item)
    {
        if (!$this->variableStack->contains($view)) {
            $this->variableStack->attach($view, new \SplStack());
        }

        foreach ($this->variableStack[$view] as $stackEntry) {
            if ($stackEntry['item'] === $item) {
                return $stackEntry['stack'];
            }
        }

        if ($view instanceof GridView) {
            $variables = array_merge(['grid' => $view, 'data' => $item]);
        } elseif ($view instanceof ColumnView) {
            $boundView = clone $view;
            $boundView->bind($item);

            $variables = array_merge(['column' => $view, 'item' => $item], $boundView->vars);
        } else {
            throw new UnexpectedTypeException(GridView::class . '|' . ColumnView::class, $view);
        }

        $stack = new \SplStack();
        $stack->push($variables);

        $this->variableStack[$view]->push([
            'item' => $item,
            'stack' => $stack,
        ]);

        return $stack;
    }
}
