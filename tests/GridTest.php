<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ColumnDescription;
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

        $grid = new Grid();
        $grid->addColumn('column', $column);

        $this->assertTrue($grid->hasColumn('column'));
        $this->assertSame($column, $grid->getColumn('column'));
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

        $columnView = new ColumnView('column');

        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $type->expects($this->once())
             ->method('createView')
             ->with('column', $options)
             ->willReturn($columnView);

        $column = new ColumnDescription($type, ['option' => 'value']);

        $grid = new Grid();
        $grid->addColumn('column', $column);

        $view = $grid->createView();

        $this->assertInstanceOf(GridView::class, $view);
        $this->assertEquals(1, count($view));
        $this->assertTrue(isset($view['column']));
        $this->assertSame($columnView, $view['column']);
    }
}
