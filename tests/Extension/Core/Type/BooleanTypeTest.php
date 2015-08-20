<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class BooleanTypeTest extends TypeTest
{
    public function testGetValue()
    {
        $data = (object)['foo' => true, 'bar' => false];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'boolean')
            ->addColumn('bar', 'boolean')
            ->getGrid();

        $view = $grid->createView();
        $view->columns['foo']->bind($data);
        $view->columns['bar']->bind($data);

        $this->assertEquals('yes', $view->columns['foo']->vars['value']);
        $this->assertEquals('no', $view->columns['bar']->vars['value']);
    }
}
