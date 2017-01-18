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
