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
     * Constructor
     *
     * @param mixed $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
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
        if ($view instanceof GridView) {
            $variables = array_merge(['grid' => $view, 'data' => $item], $variables);
        } elseif ($view instanceof ColumnView) {
            $variables = array_merge(['column' => $view, 'item' => $item], $view->vars, $variables);
        } else {
            throw new UnexpectedTypeException(GridView::class . '|' . ColumnView::class, $view);
        }

        $blockSuffix = strrchr($name, '_');
        $blockPrefix = strlen($blockSuffix) ? substr($name, 0, -strlen($blockSuffix)) : $name;

        if ('_widget' == $blockSuffix && $view instanceof ColumnView) {
            foreach ($view->vars['block_types'] as $blockType) {
                $blockName = $blockPrefix . '_' . $blockType . $blockSuffix;

                if ($this->theme->hasBlock($blockType . '_widget')) {
                    $name = $blockName;
                    break;
                }
            }
        }

        return $this->theme->renderBlock($name, $variables);
    }
}
