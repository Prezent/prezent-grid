<?php

namespace Prezent\Grid\Twig\Node;

/**
 * The 'grid_theme' node.
 *
 * Heavily inspired by Symfony\Bridge\Twig\Node\FormThemeNode
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Sander Marechal
 */
class GridThemeNode extends \Twig_Node
{
    public function __construct(\Twig_Node $grid, \Twig_Node $resources, $lineno, $tag = null)
    {
        parent::__construct(['grid' => $grid, 'resources' => $resources], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\'grid\')->renderer->setTheme(')
            ->subcompile($this->getNode('grid'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
    }
}
