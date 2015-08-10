<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class BooleanTypeTest extends TypeTest
{
    public function testGetValue()
    {
        $data = (object)['foo' => true, 'bar' => false];

        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'boolean')
            ->add('bar', 'boolean')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('yes', $view['foo']->getValue($data));
        $this->assertEquals('no', $view['bar']->getValue($data));
    }
}
