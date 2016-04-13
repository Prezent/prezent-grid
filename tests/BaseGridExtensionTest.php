<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\GridType;
use Prezent\Grid\GridTypeExtension;

class BaseGridExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->getMockForAbstractClass();

        $this->assertFalse($gridExtension->hasGridType('grid'));
        $this->assertCount(0, $gridExtension->getGridTypeExtensions('grid'));

        $this->assertFalse($gridExtension->hasElementType('column'));
        $this->assertCount(0, $gridExtension->getElementTypeExtensions('column'));
    }

    public function testLoadGrid()
    {
        $extension = $this->createExtension();

        $this->assertFalse($extension->hasGridType('invalid'));
        $this->assertTrue($extension->hasGridType('grid'));
        $this->assertInstanceOf(GridType::class, $extension->getGridType('grid'));

        $typeExtensions = $extension->getGridTypeExtensions('grid');

        $this->assertCount(1, $typeExtensions);
        $this->assertInstanceOf(GridTypeExtension::class, $typeExtensions[0]);
    }

    public function testLoadElement()
    {
        $extension = $this->createExtension();

        $this->assertFalse($extension->hasElementType('invalid'));
        $this->assertTrue($extension->hasElementType('column'));
        $this->assertInstanceOf(ElementType::class, $extension->getElementType('column'));

        $typeExtensions = $extension->getElementTypeExtensions('column');

        $this->assertCount(1, $typeExtensions);
        $this->assertInstanceOf(ElementTypeExtension::class, $typeExtensions[0]);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testUnknownGridType()
    {
        $extension = $this->createExtension();
        $extension->getGridType('invalid');
    }

    public function testUnknownGridTypeExtension()
    {
        $extension = $this->createExtension();
        $this->assertEquals([], $extension->getGridTypeExtensions('invalid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testUnknownElementType()
    {
        $extension = $this->createExtension();
        $extension->getElementType('invalid');
    }

    public function testUnknownElementTypeExtension()
    {
        $extension = $this->createExtension();
        $this->assertEquals([], $extension->getElementTypeExtensions('invalid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidGridType()
    {
        $extension = $this->createExtension(['invalid']);
        $extension->getGridType('invalid');
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidGridTypeExtension()
    {
        $extension = $this->createExtension([], ['invalid']);
        $extension->getGridTypeExtensions('invalid');
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidElementType()
    {
        $extension = $this->createExtension([], [], ['invalid']);
        $extension->getElementType('invalid');
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidElementTypeExtension()
    {
        $extension = $this->createExtension([], [], [], ['invalid']);
        $extension->getElementTypeExtensions('invalid');
    }

    private function createExtension($gridTypes = [], $gridTypeExtensions = [], $elementTypes = [], $elementTypeExtensions = [])
    {
        $gridType = $this->getMockBuilder(GridType::class)->getMock();
        $gridType->method('getName')->willReturn('grid');

        $gridTypeExtension = $this->getMockBuilder(GridTypeExtension::class)->getMock();
        $gridTypeExtension->method('getExtendedType')->willReturn('grid');

        $elementType = $this->getMockBuilder(ElementType::class)->getMock();
        $elementType->method('getName')->willReturn('column');

        $elementTypeExtension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $elementTypeExtension->method('getExtendedType')->willReturn('column');

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
