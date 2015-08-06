<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\Grid;

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
     * @param Grid $grid
     * @param mixed $data
     * @return string
     */
    public function renderBlock($name, Grid $grid, $data)
    {
        return $this->theme->renderBlock($name, ['grid' => $grid, 'data' => $data]);
    }
}
