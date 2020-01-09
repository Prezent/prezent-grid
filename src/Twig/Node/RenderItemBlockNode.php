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
    public function compile(Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $arguments = iterator_to_array($this->getNode('arguments'));

        $compiler
            ->write('$this->env->getRuntime(\''. GridRenderer::class . '\')->renderBlock(')
            ->raw('\''.$this->getAttribute('name').'\'');

        foreach ($arguments as $argument) {
            $compiler->raw(', ');
            $compiler->subcompile($argument);
        }

        $compiler->raw(')');
    }
}
