<?php

namespace Prezent\Grid\Tests\VariableResolver;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\VariableResolver;
use Prezent\Grid\VariableResolver\ChainResolver;

class ChainResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testChain()
    {
        $resolver1 = $this->createMock(VariableResolver::class);
        $resolver1->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo('foo'))
            ->willReturn('foobar');

        $resolver2 = $this->createMock(VariableResolver::class);
        $resolver2->expects($this->once())
            ->method('resolve')
            ->with($this->equalTo('foobar'))
            ->willReturn('foobarbaz');

        $chain = new ChainResolver([$resolver1, $resolver2]);
        $value = $chain->resolve('foo', []);

        $this->assertEquals('foobarbaz', $value);
    }

    public function testInvalidResolver()
    {
        $this->expectException(UnexpectedTypeException::class);

        $chain = new ChainResolver([new \stdClass()]);
    }
}
