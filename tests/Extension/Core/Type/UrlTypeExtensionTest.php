<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class UrlTypeExtensionTest extends TypeTest
{
    public function testUrl()
    {
        $data = (object)['id' => 1, 'name' => 'foobar'];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('item', 'string', [
                'url' => '/get/{name}/{id}'
            ])
            ->getGrid();

        $view = $grid->createView();
        $view->columns['item']->bind($data);

        $this->assertEquals('/get/foobar/1', $view->columns['item']->vars['url']);
    }
}
