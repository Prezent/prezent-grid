<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\Extension\Core\Type\StringType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class StringTypeTest extends TypeTest
{
    public function testDefaults()
    {
        $data = (object)['foo' => 'bar'];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);

        $this->assertEquals('bar', $view->columns['foo']->vars['value']);
    }

    public function testObjectStringCast()
    {
        $data = (object)['foo' => new \Twig_Token(\Twig_Token::TEXT_TYPE, 'foo', 0)];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);

        $this->assertStringContainsString('foo', $view->columns['foo']->vars['value']);
    }

    public function testArrayStringCast()
    {
        $data = (object)['foo' => ['bar' => 'baz']];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);

        $this->assertStringContainsString('baz', $view->columns['foo']->vars['value']);
    }

    public function testStringCastFailure()
    {
        $this->expectException(InvalidArgumentException::class);

        $data = (object)['foo' => new \stdClass()];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);
    }

    private function createGridView()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', StringType::class)
            ->getGrid();

        return $grid->createView();
    }
}
