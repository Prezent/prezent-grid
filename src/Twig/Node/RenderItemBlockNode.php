<?php

namespace Prezent\Grid\Twig\Node;

use Prezent\Grid\Twig\GridRenderer;
use Twig\Compiler;
use Twig\Node\Expression\FunctionExpression;

/**
 * Compile a grid block
 *
 * @see FunctionExpression
 * @author Sander Marechal
 */
class RenderItemBlockNode extends FunctionExpression
{
    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);
        $arguments = iterator_to_array($this->getNode('arguments'));

        $compiler
            ->write('$this->env->getRuntime(\''. GridRenderer::class . '\')->renderBlock(')
            ->raw('\''.$this->getAttribute('name').'\', ')
            ->subcompile($arguments[0]);

        // item
        if (isset($arguments[1])) {
            $compiler
                ->raw(', ')
                ->subcompile($arguments[1]);
        } else {
            $compiler->raw(', null');
        }

        // variables
        if (isset($arguments[2])) {
            $compiler
                ->raw(', ')
                ->subcompile($arguments[2]);
        } else {
            $compiler->raw(', []');
        }

        $compiler->raw(', true)');
    }
}
