<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\ResolvedElementType;

class ElementTypeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testConstruction()
    {
        $factory = new DefaultElementTypeFactory(['invalid']);
    }

    public function testGetType()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $extension = $this->createExtension([$type]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType('string'));
    }

    public function testGetTypeWithStringParent()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn('parent');

        $parent = $this->getMockBuilder(ElementType::class)->getMock();
        $parent->expects($this->atLeastOnce())->method('getName')->willReturn('parent');

        $extension = $this->createExtension([$type, $parent]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType('string'));
    }

    public function testGetTypeWithObjectParent()
    {
        $parent = $this->getMockBuilder(ElementType::class)->getMock();

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn($parent);

        $extension = $this->createExtension([$type]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType('string'));
    }

    public function testGetTypeWithExtension()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $typeExtension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn('string');

        $extension = $this->createExtension([$type], [$typeExtension]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType('string'));
    }

    public function testResolveUnregisteredType()
    {
        $extension = $this->createExtension();
        $factory = new DefaultElementTypeFactory([$extension]);

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithParent()
    {
        $parent = $this->getMockBuilder(ElementType::class)->getMock();
        $parent->expects($this->atLeastOnce())->method('getName')->willReturn('parent');

        $extension = $this->createExtension([$parent]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn('parent');

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithExtension()
    {
        $typeExtension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn('string');

        $extension = $this->createExtension([], [$typeExtension]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getName')->willReturn('string');

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testGetTypeNotString()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $factory = new DefaultElementTypeFactory([]);

        $factory->getType($type);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetTypeNotFound()
    {
        $factory = new DefaultElementTypeFactory([]);
        $factory->getType('string');
    }

    private function createExtension(array $types = [], array $extensions = [])
    {
        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->setMethods(['loadElementTypes', 'loadElementTypeExtensions'])
            ->getMockForAbstractClass();

        $gridExtension->method('loadElementTypes')->willReturn($types);
        $gridExtension->method('loadElementTypeExtensions')->willReturn($extensions);

        return $gridExtension;
    }
}
