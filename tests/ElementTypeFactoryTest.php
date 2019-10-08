<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\Extension\Core\Type;
use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\ResolvedElementType;

class ElementTypeFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruction()
    {
        $this->expectException(UnexpectedTypeException::class);

        $factory = new DefaultElementTypeFactory(['invalid']);
    }

    public function testGetType()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();

        $extension = $this->createExtension([$type]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType(get_class($type)));
    }

    public function testGetTypeWithStringParent()
    {
        $parent = $this->getMockBuilder(ElementType::class)->setMockClassName('MockParentType')->getMock();

        $type = $this->getMockBuilder(ElementType::class)->setMockClassName('MockType')->getMock();
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn(get_class($parent));

        $extension = $this->createExtension([$type, $parent]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType(get_class($type)));
    }

    public function testGetTypeWithObjectParent()
    {
        $parent = $this->getMockBuilder(ElementType::class)->getMock();

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn($parent);

        $extension = $this->createExtension([$type]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType(get_class($parent)));
    }

    public function testGetTypeWithExtension()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();

        $typeExtension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn(get_class($type));

        $extension = $this->createExtension([$type], [$typeExtension]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->getType(get_class($type)));
    }

    public function testResolveUnregisteredType()
    {
        $extension = $this->createExtension();
        $factory = new DefaultElementTypeFactory([$extension]);

        $type = $this->getMockBuilder(ElementType::class)->getMock();

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithParent()
    {
        $parent = $this->getMockBuilder(ElementType::class)->getMock();

        $extension = $this->createExtension([$parent]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->expects($this->atLeastOnce())->method('getParent')->willReturn(get_class($parent));

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    public function testResolveUnregisteredTypeWithExtension()
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();

        $typeExtension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $typeExtension->expects($this->atLeastOnce())->method('getExtendedType')->willReturn(get_class($type));

        $extension = $this->createExtension([], [$typeExtension]);
        $factory = new DefaultElementTypeFactory([$extension]);

        $this->assertInstanceOf(ResolvedElementType::class, $factory->resolveType($type));
    }

    public function testGetTypeNotString()
    {
        $this->expectException(UnexpectedTypeException::class);

        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $factory = new DefaultElementTypeFactory([]);

        $factory->getType($type);
    }

    public function testGetTypeNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new DefaultElementTypeFactory([]);
        $factory->getType('does-not-exist');
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
