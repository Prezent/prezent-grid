<?php

namespace Prezent\Grid\Tests\Extension\Core;

use Prezent\Grid\Extension\Core\GridType;

class GridTypeTest extends TypeTest
{
    public function testAttributes()
    {
        $grid = $this->gridFactory->createGrid(GridType::class, ['attr' => ['class' => 'foo']]);
        $view = $grid->createView();

        $this->assertEquals('foo', $view->vars['attr']['class']);
    }
}
