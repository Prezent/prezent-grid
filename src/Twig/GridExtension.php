<?php

namespace Prezent\Grid\Twig;

use Prezent\Grid\Twig\Node\RenderBlockNode;
use Prezent\Grid\Twig\Node\RenderItemBlockNode;
use Prezent\Grid\Twig\TokenParser\GridThemeTokenParser;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\TwigFilter;
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

    public function getFilters()
    {
        return [
            new TwigFilter('truncate', [$this, 'truncate'], ['needs_environment' => true, 'is_safe' => ['html']]),
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
     * Truncate a string, taken from twig/extensions
     *
     * Ported to the GridExtension so this extension can be compatible with both Twig 2 and Twig 3 without
     * changing the base grid.html.twig template.
     *
     * @see: https://github.com/twigphp/Twig-extensions/blob/master/src/TextExtension.php#L36
     */
    public function truncate(Environment $env, $value, $length = 30, $preserve = false, $separator = '...')
    {
        if (mb_strlen($value, $env->getCharset()) > $length) {
            if ($preserve) {
                // If breakpoint is on the last word, return the value without separator.
                if (false === ($breakpoint = mb_strpos($value, ' ', $length, $env->getCharset()))) {
                    return $value;
                }
                $length = $breakpoint;
            }
            return rtrim(mb_substr($value, 0, $length, $env->getCharset())).$separator;
        }
        return $value;
    }
}
