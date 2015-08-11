<?php

namespace Prezent\Grid\Tests\VariableResolver;

use Prezent\Grid\VariableResolver;
use Prezent\Grid\VariableResolver\ChainResolver;

class ChainResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testChain()
    {
        $resolver1 = $this->getMock(VariableResolver::class);
        $resolver1->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo('foo'))
            ->willReturn('foobar');

        $resolver2 = $this->getMock(VariableResolver::class);
        $resolver2->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo('foobar'))
            ->willReturn('foobarbaz');

        $chain = new ChainResolver([$resolver1, $resolver2]);
        $value = $chain->resolve('foo', []);

        $this->assertEquals('foobarbaz', $value);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidResolver()
    {
        $chain = new ChainResolver([new \stdClass()]);
    }
}
