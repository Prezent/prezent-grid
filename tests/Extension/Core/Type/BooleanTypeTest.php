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

        $this->assertEquals('yes', $view->columns['foo']->getValue($data));
        $this->assertEquals('no', $view->columns['bar']->getValue($data));
    }
}
