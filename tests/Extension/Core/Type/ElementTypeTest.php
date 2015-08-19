<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class ElementTypeTest extends TypeTest
{
    public function testDefaultsFromName()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'element')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('foo', $view->columns['foo']->vars['label']);
    }

    public function testEmptyLabel()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'column', ['label' => false])
            ->getGrid();

        $view = $grid->createView();

        $this->assertFalse($view->columns['foo']->vars['label']);
    }

    public function testBlockTypes()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals(['string', 'column', 'element'], $view->columns['foo']->vars['block_types']);
    }
}
