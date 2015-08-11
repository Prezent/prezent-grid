<?php

namespace Prezent\Grid\Tests\VariableResolver;

use Prezent\Grid\VariableResolver\CallbackResolver;

class CallbackResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testCallback()
    {
        $resolver = new CallbackResolver();
        $data = new \stdClass();

        $this->assertEquals('invalid', $resolver->resolve('invalid', $data));

        $param = null;
        $value = $resolver->resolve(function ($item) use (&$param) {
            $param = $item;
            return 'foo';
        }, $data);

        $this->assertEquals('foo', $value);
        $this->assertSame($data, $param);
    }
}
