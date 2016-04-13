<?php

namespace Prezent\Grid\Tests\Extension\Core;

class GridTypeTest extends TypeTest
{
    public function testAttributes()
    {
        $grid = $this->gridFactory->createGrid('grid', ['attr' => ['class' => 'foo']]);
        $view = $grid->createView();

        $this->assertEquals('foo', $view->vars['attr']['class']);
    }
}
