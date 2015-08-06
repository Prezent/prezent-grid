<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ColumnType;
use Prezent\Grid\ColumnTypeExtension;
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
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $extension = $this->createExtension([$type]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testGetTypeWithStringParent()
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn('parent');

        $parent = $this->getMockBuilder(ColumnType::class)->getMock();
        $parent->expects($this->atLeastOnce())->method('getName')->willReturn('parent');

        $extension = $this->createExtension([$type, $parent]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testGetTypeWithObjectParent()
    {
        $parent = $this->getMockBuilder(ColumnType::class)->getMock();

        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn($parent);

        $extension = $this->createExtension([$type]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testGetTypeWithExtension()
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $typeExtension = $this->getMockBuilder(ColumnTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn('string');

        $extension = $this->createExtension([$type], [$typeExtension]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->getType('string'));
    }

    public function testResolveUnregisteredType()
    {
        $extension = $this->createExtension();
        $factory = new DefaultColumnTypeFactory([$extension]);

        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithParent()
    {
        $parent = $this->getMockBuilder(ColumnType::class)->getMock();
        $parent->expects($this->atLeastOnce())->method('getName')->willReturn('parent');

        $extension = $this->createExtension([$parent]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn('parent');

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithExtension()
    {
        $typeExtension = $this->getMockBuilder(ColumnTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn('string');

        $extension = $this->createExtension([], [$typeExtension]);
        $factory = new DefaultColumnTypeFactory([$extension]);

        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $this->assertInstanceOf(ResolvedColumnType::class, $factory->resolveType($type));
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
