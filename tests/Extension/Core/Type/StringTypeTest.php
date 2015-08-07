<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class StringTypeTest extends TypeTest
{
    public function testDefaults()
    {
        $data = (object)['foo' => 'bar'];

        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'string')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('bar', $view['foo']->getValue($data));
    }
}
