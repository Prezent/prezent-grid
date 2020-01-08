<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\Twig\Node\RenderBlockNode;
use Prezent\Grid\Twig\Node\RenderItemBlockNode;
use Prezent\Grid\Twig\TokenParser\GridThemeTokenParser;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\TwigFunction;

/**
 * Extend twig with grid functions
 *
 * @see AbstractExtension
 * @author Sander Marechal
 */
class GridExtension extends AbstractExtension implements RuntimeExtensionInterface
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
    public function initRuntime(Environment $environment)
    {
        $this->renderer->setEnvironment($environment);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('grid', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_header_row', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_header_column', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_header_widget', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_row', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_column', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_widget', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_actions', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
            new TwigFunction('grid_action', null, ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']]),
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
}
