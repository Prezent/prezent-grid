<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Extension\Core\Type\StringType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class VisibleTypeExtensionTest extends TypeTest
{
    /**
     * @dataProvider visibleProvider
     */
    public function testVisible($data, $visible, $expected)
    {
        $grid = $this->gridFactory->createBuilder()
            ->addAction('test', [
                'visible' => $visible,
            ])
            ->getGrid();

        $view = $grid->createView();

        $view->actions['test']->bind($data);
        $this->assertEquals($expected, $view->actions['test']->vars['visible']);
    }

    public function visibleProvider()
    {
        return [
            [(object)['id' => 1, 'active' => false], true, true],
            [(object)['id' => 1, 'active' => true], 'active', true],
            [(object)['id' => 1, 'active' => false], 'active', false],
            [(object)['id' => 1, 'active' => true], function ($data) { return $data->active; }, true],
            [(object)['id' => 1, 'active' => false], function ($data) { return $data->active; }, false],
        ];
    }
}
