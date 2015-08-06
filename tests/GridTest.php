<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ColumnDescription;
use Prezent\Grid\ColumnType;
use Prezent\Grid\ColumnView;
use Prezent\Grid\Grid;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedColumnType;

class GridTest extends \PHPUnit_Framework_TestCase
{
    public function testColumns()
    {
        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $column = new ColumnDescription($type, ['option' => 'value']);

        $grid = new Grid(['column' => $column]);

        $this->assertTrue($grid->hasColumn('column'));
        $this->assertSame($column, $grid->getColumn('column'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testUnnamedColumn()
    {
        $column = $this->getMockBuilder(ColumnDescription::class)->disableOriginalConstructor()->getMock();
        $grid = new Grid([$column]);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidColumn()
    {
        $grid = new Grid(['column' => 'invalid']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testColumnNotFound()
    {
        $grid = new Grid();
        $this->assertFalse($grid->hasColumn('column'));

        $grid->getColumn('column');
    }

    public function testCreateView()
    {
        $options = ['option' => 'value'];

        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();

        $columnView = new ColumnView('column', $type);

        $type->expects($this->once())
             ->method('createView')
             ->with('column', $options)
             ->willReturn($columnView);

        $column = new ColumnDescription($type, ['option' => 'value']);

        $grid = new Grid(['column' => $column]);

        $view = $grid->createView();

        $this->assertInstanceOf(GridView::class, $view);
        $this->assertEquals(1, count($view));
        $this->assertTrue(isset($view['column']));
        $this->assertSame($columnView, $view['column']);
    }
}
