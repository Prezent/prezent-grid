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
class RenderBlockNode extends FunctionExpression
{
    public function compile(Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $arguments = iterator_to_array($this->getNode('arguments'));
        $class = \Prezent\Grid\Twig\GridExtension::class;

        $compiler
            ->write('$this->env->getExtension(\''.$class.'\')->renderer->renderBlock(')
            ->raw('\''.$this->getAttribute('name').'\', ')
            ->subcompile($arguments[0]);

        if (isset($arguments[1])) {
            $compiler
                ->raw(', null, ')
                ->subcompile($arguments[1]);
        }

        $compiler->raw(')');
    }
}
