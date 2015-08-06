<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ColumnType;
use Prezent\Grid\DefaultColumnTypeFactory;
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
        $type->expects($this->once())->method('getName')->willReturn('string');

        $extension = $this->createExtension([$type]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testGetParentTypeString()
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->once())->method('getName')->willReturn('string');
        $type->expects($this->once())->method('getParent')->willReturn('parent');

        $parent = $this->getMockBuilder(ColumnType::class)->getMock();
        $parent->expects($this->once())->method('getName')->willReturn('parent');

        $extension = $this->createExtension([$type, $parent]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testGetParentTypeObject()
    {
        $parent = $this->getMockBuilder(ColumnType::class)->getMock();

        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->once())->method('getName')->willReturn('string');
        $type->expects($this->once())->method('getParent')->willReturn($parent);

        $extension = $this->createExtension([$type]);
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

    private function createExtension(array $types = [], array $extensions = [])
    {
        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->setMethods(['loadColumnTypes', 'loadColumnTypeExtensions'])
            ->getMockForAbstractClass();

        $gridExtension->method('loadColumnTypes')->willReturn($types);
        $gridExtension->method('loadColumnTypeExtensions')->willReturn($extensions);

        return $gridExtension;
    }
}
