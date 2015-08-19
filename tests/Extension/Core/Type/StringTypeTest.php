<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class StringTypeTest extends TypeTest
{
    public function testDefaults()
    {
        $data = (object)['foo' => 'bar'];
        $view = $this->createGridView();

        $this->assertEquals('bar', $view->columns['foo']->getValue($data));
    }

    public function testObjectStringCast()
    {
        $data = (object)['foo' => new \Twig_Token(\Twig_Token::TEXT_TYPE, 'foo', 0)];
        $view = $this->createGridView();

        $this->assertContains('foo', $view->columns['foo']->getValue($data));
    }

    public function testArrayStringCast()
    {
        $data = (object)['foo' => ['bar' => 'baz']];
        $view = $this->createGridView();

        $this->assertContains('baz', $view->columns['foo']->getValue($data));
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testStringCastFailure()
    {
        $data = (object)['foo' => new \stdClass()];
        $view = $this->createGridView();

        $view->columns['foo']->getValue($data);
    }

    private function createGridView()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string')
            ->getGrid();

        return $grid->createView();
    }
}
