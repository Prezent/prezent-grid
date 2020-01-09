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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $blockOptions = ['node_class' => RenderBlockNode::class, 'is_safe' => ['html'], ''];
        $blockItemOptions = ['node_class' => RenderItemBlockNode::class, 'is_safe' => ['html']];

        return [
            new TwigFunction('grid', null, $blockItemOptions),
            new TwigFunction('grid_header_row', null, $blockOptions),
            new TwigFunction('grid_header_column', null, $blockOptions),
            new TwigFunction('grid_header_widget', null, $blockOptions),
            new TwigFunction('grid_row', null, $blockItemOptions),
            new TwigFunction('grid_column', null, $blockItemOptions),
            new TwigFunction('grid_widget', null, $blockItemOptions),
            new TwigFunction('grid_actions', null, $blockItemOptions),
            new TwigFunction('grid_action', null, $blockItemOptions),
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
