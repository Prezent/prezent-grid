<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Extension\Core\Type\StringType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class UrlTypeExtensionTest extends TypeTest
{
    public function testUrl()
    {
        $data = (object)['id' => 1, 'name' => 'foobar'];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('item', StringType::class, [
                'url' => '/get/{name}/{id}',
                'property_path' => 'id',
            ])
            ->getGrid();

        $view = $grid->createView();
        $view->columns['item']->bind($data);

        $this->assertEquals('/get/foobar/1', $view->columns['item']->vars['url']);
    }
}
