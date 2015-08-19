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

        $this->assertEquals('foo', $view->columns['foo']->vars['label']);
        $this->assertEquals('foo', $view->columns['foo']->vars['property_path']);
    }

    public function testEmptyLabel()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'column', ['label' => false])
            ->getGrid();

        $view = $grid->createView();

        $this->assertFalse($view->columns['foo']->vars['label']);
    }

    public function testGetValue()
    {
        $data = new \stdClass();
        $data->one = 'col1';
        $data->other = 'col2';

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('one', 'column')
            ->addColumn('two', 'column', ['property_path' => 'other'])
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('col1', $view->columns['one']->getValue($data));
        $this->assertEquals('col2', $view->columns['two']->getValue($data));
    }

    public function testBlockTypes()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals(['string', 'column'], $view->columns['foo']->vars['block_types']);
    }
}
