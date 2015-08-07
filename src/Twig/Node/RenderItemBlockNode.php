<?php

namespace Prezent\Grid\Twig\Node;

/**
 * Compile a grid block
 *
 * @see \Twig_Node_Expression_Function
 * @author Sander Marechal
 */
class RenderItemBlockNode extends \Twig_Node_Expression_Function
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $arguments = iterator_to_array($this->getNode('arguments'));

        $compiler
            ->write('$this->env->getExtension(\'grid\')->renderer->renderBlock(')
            ->raw('\''.$this->getAttribute('name').'\'');

        foreach ($arguments as $argument) {
            $compiler->raw(', ');
            $compiler->subcompile($argument);
        }

        $compiler->raw(')');
    }
}
