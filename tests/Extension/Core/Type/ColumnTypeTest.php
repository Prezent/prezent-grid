<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class ColumnTypeTest extends TypeTest
{
    public function testDefaultsFromName()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'column')
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
            ->addColumn('one', 'column')
            ->addColumn('two', 'column', ['property_path' => 'other'])
            ->getGrid();

        $view = $grid->createView();
        $view->columns['one']->bind($data);
        $view->columns['two']->bind($data);

        $this->assertEquals('col1', $view->columns['one']->vars['value']);
        $this->assertEquals('col2', $view->columns['two']->vars['value']);
    }
}
