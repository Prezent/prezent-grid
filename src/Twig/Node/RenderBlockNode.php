<?php

namespace Prezent\Grid\Twig\Node;

/**
 * Compile a grid block
 *
 * @see \Twig_Node_Expression_Function
 * @author Sander Marechal
 */
class RenderBlockNode extends \Twig_Node_Expression_Function
{
    public function compile(\Twig_Compiler $compiler)
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
