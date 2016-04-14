<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Extension\Core\Type\BooleanType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class BooleanTypeTest extends TypeTest
{
    public function testGetValue()
    {
        $data = (object)['foo' => true, 'bar' => false];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', BooleanType::class)
            ->addColumn('bar', BooleanType::class)
            ->getGrid();

        $view = $grid->createView();
        $view->columns['foo']->bind($data);
        $view->columns['bar']->bind($data);

        $this->assertEquals('yes', $view->columns['foo']->vars['value']);
        $this->assertEquals('no', $view->columns['bar']->vars['value']);
    }
}
