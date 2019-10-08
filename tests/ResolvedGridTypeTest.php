<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\GridType;
use Prezent\Grid\GridTypeExtension;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedGridType;

class ResolvedGridTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruction()
    {
        $type = $this->createMock(GridType::class);
        $extension = $this->createMock(GridTypeExtension::class);
        $parent = $this->getMockBuilder(ResolvedGridType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resolvedType = new ResolvedGridType($type, [$extension], $parent);

        $this->assertInstanceOf(ResolvedGridType::class, $resolvedType);
    }

    public function testInvalidConstruction()
    {
        $this->expectException(UnexpectedTypeException::class);

        $type = $this->createMock(GridType::class);
        $resolvedType = new ResolvedGridType($type, ['invalid']);
    }

    public function testBuildGrid()
    {
        $type = $this->createMock(GridType::class);
        $type->expects($this->once())->method('buildGrid');

        $extension = $this->createMock(GridTypeExtension::class);
        $extension->expects($this->once())->method('buildGrid');

        $parent = $this->getMockBuilder(ResolvedGridType::class)->disableOriginalConstructor()->getMock();
        $parent->expects($this->once())->method('buildGrid');

        $resolvedType = new ResolvedGridType($type, [$extension], $parent);

        $builder = $this->getMockBuilder(GridBuilder::class)->disableOriginalConstructor()->getMock();

        $resolvedType->buildGrid($builder, []);
    }

    public function testBuildView()
    {
        $type = $this->createMock(GridType::class);
        $type->expects($this->once())->method('buildView');

        $extension = $this->createMock(GridTypeExtension::class);
        $extension->expects($this->once())->method('buildView');

        $parent = $this->getMockBuilder(ResolvedGridType::class)->disableOriginalConstructor()->getMock();
        $parent->expects($this->once())->method('buildView');

        $resolvedType = new ResolvedGridType($type, [$extension], $parent);
        $resolvedType->buildView($this->createMock(GridView::class), []);
    }
}
