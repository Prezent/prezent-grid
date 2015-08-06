<?php

namespace Prezent\Grid\Twig;

/**
 * Extend twig with grid functions
 *
 * @see \Twig_Extension
 * @author Sander Marechal
 */
class GridExtension extends \Twig_Extension
{
    /**
     * @var GridRenderer The renderer is public for faster access in compiled twig templates
     */
    public $renderer;

    /**
     * Constructor
     *
     * @param GridRenderer $renderer
     */
    public function __construct(GridRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->renderer->setEnvironment($environment);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('grid', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'grid';
    }
}
