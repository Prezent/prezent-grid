<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class ColumnTypeTest extends TypeTest
{
    public function testDefaultsFromName()
    {
        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'column')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('foo', $view['foo']->vars['label']);
        $this->assertEquals('foo', $view['foo']->vars['property_path']);
    }

    public function testEmptyLabel()
    {
        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'column', ['label' => false])
            ->getGrid();

        $view = $grid->createView();

        $this->assertFalse($view['foo']->vars['label']);
    }

    public function testGetValue()
    {
        $data = new \stdClass();
        $data->one = 'col1';
        $data->other = 'col2';

        $grid = $this->gridFactory->createBuilder()
            ->add('one', 'column')
            ->add('two', 'column', ['property_path' => 'other'])
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('col1', $view['one']->getValue($data));
        $this->assertEquals('col2', $view['two']->getValue($data));
    }
}
