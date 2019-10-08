<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\GridType;
use Prezent\Grid\GridTypeExtension;

class BaseGridExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->getMockForAbstractClass();

        $this->assertFalse($gridExtension->hasGridType(GridType::class));
        $this->assertCount(0, $gridExtension->getGridTypeExtensions(GridType::class));

        $this->assertFalse($gridExtension->hasElementType(ElementType::class));
        $this->assertCount(0, $gridExtension->getElementTypeExtensions(ElementType::class));
    }

    public function testLoadGrid()
    {
        $extension = $this->createExtension();

        $this->assertFalse($extension->hasGridType('invalid'));
        $this->assertTrue($extension->hasGridType('MockGridType'));
        $this->assertInstanceOf(GridType::class, $extension->getGridType('MockGridType'));

        $typeExtensions = $extension->getGridTypeExtensions('MockGridType');

        $this->assertCount(1, $typeExtensions);
        $this->assertInstanceOf(GridTypeExtension::class, $typeExtensions[0]);
    }

    public function testLoadElement()
    {
        $extension = $this->createExtension();

        $this->assertFalse($extension->hasElementType('invalid'));
        $this->assertTrue($extension->hasElementType('MockElementType'));
        $this->assertInstanceOf(ElementType::class, $extension->getElementType('MockElementType'));

        $typeExtensions = $extension->getElementTypeExtensions('MockElementType');

        $this->assertCount(1, $typeExtensions);
        $this->assertInstanceOf(ElementTypeExtension::class, $typeExtensions[0]);
    }

    public function testUnknownGridType()
    {
        $this->expectException(InvalidArgumentException::class);

        $extension = $this->createExtension();
        $extension->getGridType('invalid');
    }

    public function testUnknownGridTypeExtension()
    {
        $extension = $this->createExtension();
        $this->assertEquals([], $extension->getGridTypeExtensions('invalid'));
    }

    public function testUnknownElementType()
    {
        $this->expectException(InvalidArgumentException::class);

        $extension = $this->createExtension();
        $extension->getElementType('invalid');
    }

    public function testUnknownElementTypeExtension()
    {
        $extension = $this->createExtension();
        $this->assertEquals([], $extension->getElementTypeExtensions('invalid'));
    }

    public function testInvalidGridType()
    {
        $this->expectException(UnexpectedTypeException::class);

        $extension = $this->createExtension(['invalid']);
        $extension->getGridType('invalid');
    }

    public function testInvalidGridTypeExtension()
    {
        $this->expectException(UnexpectedTypeException::class);

        $extension = $this->createExtension([], ['invalid']);
        $extension->getGridTypeExtensions('invalid');
    }

    public function testInvalidElementType()
    {
        $this->expectException(UnexpectedTypeException::class);

        $extension = $this->createExtension([], [], ['invalid']);
        $extension->getElementType('invalid');
    }

    public function testInvalidElementTypeExtension()
    {
        $this->expectException(UnexpectedTypeException::class);

        $extension = $this->createExtension([], [], [], ['invalid']);
        $extension->getElementTypeExtensions('invalid');
    }

    private function createExtension($gridTypes = [], $gridTypeExtensions = [], $elementTypes = [], $elementTypeExtensions = [])
    {
        $gridType = $this->getMockBuilder(GridType::class)->setMockClassName('MockGridType')->getMock();

        $gridTypeExtension = $this->getMockBuilder(GridTypeExtension::class)->setMockClassName('MockGridTypeExtension')->getMock();
        $gridTypeExtension->method('getExtendedType')->willReturn(get_class($gridType));

        $elementType = $this->getMockBuilder(ElementType::class)->setMockClassName('MockElementType')->getMock();

        $elementTypeExtension = $this->getMockBuilder(ElementTypeExtension::class)->setMockClassName('MockElementTypeExtension')->getMock();
        $elementTypeExtension->method('getExtendedType')->willReturn(get_class($elementType));

        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->setMethods(['loadGridTypes', 'loadGridTypeExtensions', 'loadElementTypes', 'loadElementTypeExtensions'])
            ->getMockForAbstractClass();

        $gridTypes[] = $gridType;
        $gridTypeExtensions[] = $gridTypeExtension;

        $elementTypes[] = $elementType;
        $elementTypeExtensions[] = $elementTypeExtension;

        $gridExtension->method('loadGridTypes')->willReturn($gridTypes);
        $gridExtension->method('loadGridTypeExtensions')->willReturn($gridTypeExtensions);

        $gridExtension->method('loadElementTypes')->willReturn($elementTypes);
        $gridExtension->method('loadElementTypeExtensions')->willReturn($elementTypeExtensions);

        return $gridExtension;
    }
}
