<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

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

        $this->assertContains('foo', $view->columns['foo']->vars['value']);
    }

    public function testArrayStringCast()
    {
        $data = (object)['foo' => ['bar' => 'baz']];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);

        $this->assertContains('baz', $view->columns['foo']->vars['value']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testStringCastFailure()
    {
        $data = (object)['foo' => new \stdClass()];

        $view = $this->createGridView();
        $view->columns['foo']->bind($data);
    }

    private function createGridView()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string')
            ->getGrid();

        return $grid->createView();
    }
}
