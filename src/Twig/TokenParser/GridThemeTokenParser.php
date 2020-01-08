<?php

namespace Prezent\Grid\Twig\TokenParser;

use Prezent\Grid\Twig\Node\GridThemeNode;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Token Parser for the 'grid_theme' tag.
 *
 * Heavily based on Symfony\Bridge\Twig\TokenParser\FormThemeTokenParser
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Sander Marechal
 */
class GridThemeTokenParser extends AbstractTokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param Token $token A Token instance
     *
     * @return Node A Node instance
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $grid = $this->parser->getExpressionParser()->parseExpression();

        if ($this->parser->getStream()->test(Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();
            $resources = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $resources = new ArrayExpression([], $stream->getCurrent()->getLine());
            do {
                $resources->addElement($this->parser->getExpressionParser()->parseExpression());
            } while (!$stream->test(Token::BLOCK_END_TYPE));
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        return new GridThemeNode($grid, $resources, $lineno, $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'grid_theme';
    }
}
