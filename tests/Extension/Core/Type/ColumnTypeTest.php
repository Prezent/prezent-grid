<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Extension\Core\Type\ColumnType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class ColumnTypeTest extends TypeTest
{
    public function testDefaultsFromName()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', ColumnType::class)
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('foo', $view->columns['foo']->vars['property_path']);
    }

    public function testValue()
    {
        $data = new \stdClass();
        $data->one = 'col1';
        $data->other = 'col2';

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('one', ColumnType::class)
            ->addColumn('two', ColumnType::class, ['property_path' => 'other'])
            ->addColumn('three', ColumnType::class, ['property_path' => function ($item) {
                return get_class($item);
            }])
            ->getGrid();

        $view = $grid->createView();
        $view->columns['one']->bind($data);
        $view->columns['two']->bind($data);
        $view->columns['three']->bind($data);

        $this->assertEquals('col1', $view->columns['one']->vars['value']);
        $this->assertEquals('col2', $view->columns['two']->vars['value']);
        $this->assertEquals('stdClass', $view->columns['three']->vars['value']);
    }
}
