<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\Twig\Node\RenderBlockNode;
use Prezent\Grid\Twig\Node\RenderItemBlockNode;
use Prezent\Grid\Twig\TokenParser\GridThemeTokenParser;

/**
 * Extend twig with grid functions
 *
 * @see \Twig_Extension
 * @author Sander Marechal
 */
class GridExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
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
            new \Twig_SimpleFunction('grid', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_header_row', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_header_column', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_header_widget', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_row', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_column', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_widget', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_actions', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new \Twig_SimpleFunction('grid_action', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new GridThemeTokenParser(),
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
