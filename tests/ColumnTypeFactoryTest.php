<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ColumnType;
use Prezent\Grid\DefaultColumnTypeFactory;
use Prezent\Grid\GridExtension;
use Prezent\Grid\ResolvedColumnType;

class ColumnTypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testConstruction()
    {
        $factory = new DefaultColumnTypeFactory(['invalid']);
    }

    public function testGetType()
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();

        $extension = $this->getMockBuilder(GridExtension::class)->getMock();
        $extension->method('hasColumnType')->willReturn(true);
        $extension->method('getColumnType')->willReturn($type);

        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testGetTypeNotString()
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $factory = new DefaultColumnTypeFactory([]);

        $factory->getType($type);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetTypeNotFound()
    {
        $factory = new DefaultColumnTypeFactory([]);
        $factory->getType('string');
    }
}
