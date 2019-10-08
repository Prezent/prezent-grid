<?php

namespace Prezent\Grid\Tests\VariableResolver;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\VariableResolver\PropertyPathResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyPathResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testPropertyPath()
    {
        $resolver = new PropertyPathResolver(PropertyAccess::createPropertyAccessor());

        $data = new \stdClass();
        $data->foo = 'bar';

        $value = $resolver->resolve('Value is {foo}', $data);
        $this->assertEquals('Value is bar', $value);
    }

    public function testInvalidVariable()
    {
        $resolver = new PropertyPathResolver(PropertyAccess::createPropertyAccessor());

        $value = $resolver->resolve(['foo' => 'bar'], ['baz' => 'quu']);
        $this->assertEquals(['foo' => 'bar'], $value);
    }

    public function testInvalidPropertyPath()
    {
        $this->expectException(InvalidArgumentException::class);

        $resolver = new PropertyPathResolver(PropertyAccess::createPropertyAccessor());

        $data = new \stdClass();

        $value = $resolver->resolve('Value is {foo}', $data);
    }

    public function testEscape()
    {
        $resolver = new PropertyPathResolver(PropertyAccess::createPropertyAccessor());

        $data = new \stdClass();
        $data->foo = 'bar';

        $value = $resolver->resolve('Value of \{foo\} is {foo}', $data);
        $this->assertEquals('Value of {foo} is bar', $value);
    }
}
