<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\ColumnType;
use Prezent\Grid\ColumnTypeExtension;

class BaseGridExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $extension = $this->createExtension();

        $this->assertFalse($extension->hasColumnType('invalid'));
        $this->assertTrue($extension->hasColumnType('column'));
        $this->assertInstanceOf(ColumnType::class, $extension->getColumntype('column'));

        $typeExtensions = $extension->getColumnTypeExtensions('column');

        $this->assertCount(1, $typeExtensions);
        $this->assertInstanceOf(ColumnTypeExtension::class, $typeExtensions[0]);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testUnknownColumnType()
    {
        $extension = $this->createExtension();
        $extension->getColumnType('invalid');
    }

    public function testUnknownColumnTypeExtension()
    {
        $extension = $this->createExtension();
        $this->assertEquals([], $extension->getColumnTypeExtensions('invalid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidColumnType()
    {
        $extension = $this->createExtension(['invalid']);
        $extension->getColumnType('invalid');
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidColumnTypeExtension()
    {
        $extension = $this->createExtension([], ['invalid']);
        $extension->getColumnTypeExtensions('invalid');
    }

    private function createExtension($types = [], $extensions = [])
    {
        $type = $this->getMockBuilder(ColumnType::class)->getMock();
        $type->method('getName')->willReturn('column');

        $types[] = $type;

        $extension = $this->getMockBuilder(ColumnTypeExtension::class)->getMock();
        $extension->method('getExtendedType')->willReturn('column');

        $extensions[] = $extension;

        $gridExtension = $this->getMockBuilder(BaseGridExtension::class)
            ->setMethods(['loadColumnTypes', 'loadColumnTypeExtensions'])
            ->getMockForAbstractClass();

        $gridExtension->method('loadColumnTypes')->willReturn($types);
        $gridExtension->method('loadColumnTypeExtensions')->willReturn($extensions);

        return $gridExtension;
    }
}
