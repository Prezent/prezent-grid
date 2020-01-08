<?php

namespace Prezent\Grid\Twig\Node;

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
        $class = \Prezent\Grid\Twig\GridExtension::class;

        $compiler
            ->write('$this->env->getExtension(\''.$class.'\')->renderer->renderBlock(')
            ->raw('\''.$this->getAttribute('name').'\'');

        foreach ($arguments as $argument) {
            $compiler->raw(', ');
            $compiler->subcompile($argument);
        }

        $compiler->raw(')');
    }
}
