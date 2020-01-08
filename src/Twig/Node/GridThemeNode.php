<?php

namespace Prezent\Grid\Twig\Node;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * The 'grid_theme' node.
 *
 * Heavily inspired by Symfony\Bridge\Twig\Node\FormThemeNode
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Sander Marechal
 */
class GridThemeNode extends Node
{
    public function __construct(Node $grid, Node $resources, $lineno, $tag = null)
    {
        parent::__construct(['grid' => $grid, 'resources' => $resources], [], $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Compiler $compiler A Twig_Compiler instance
     */
    public function compile(Compiler $compiler)
    {
        $class = \Prezent\Grid\Twig\GridExtension::class;

        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getExtension(\''.$class.'\')->renderer->setTheme(')
            ->subcompile($this->getNode('grid'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
    }
}
