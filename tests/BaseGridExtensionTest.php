<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;

class BaseGridExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->getMockForAbstractClass();

        $this->assertFalse($gridExtension->hasElementType('column'));
        $this->assertCount(0, $gridExtension->getElementTypeExtensions('column'));
    }

    public function testLoad()
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
    public function testInvalidElementType()
    {
        $extension = $this->createExtension(['invalid']);
        $extension->getElementType('invalid');
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidElementTypeExtension()
    {
        $extension = $this->createExtension([], ['invalid']);
        $extension->getElementTypeExtensions('invalid');
    }

    private function createExtension($types = [], $extensions = [])
    {
        $type = $this->getMockBuilder(ElementType::class)->getMock();
        $type->method('getName')->willReturn('column');

        $types[] = $type;

        $extension = $this->getMockBuilder(ElementTypeExtension::class)->getMock();
        $extension->method('getExtendedType')->willReturn('column');

        $extensions[] = $extension;

        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->setMethods(['loadElementTypes', 'loadElementTypeExtensions'])
            ->getMockForAbstractClass();

        $gridExtension->method('loadElementTypes')->willReturn($types);
        $gridExtension->method('loadElementTypeExtensions')->willReturn($extensions);

        return $gridExtension;
    }
}
