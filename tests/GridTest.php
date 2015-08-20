<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ElementDescription;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Grid;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedElementType;

class GridTest extends \PHPUnit_Framework_TestCase
{
    public function testColumns()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $column = new ElementDescription($type, ['option' => 'value']);

        $grid = new Grid(['column' => $column]);

        $this->assertTrue($grid->hasColumn('column'));
        $this->assertSame($column, $grid->getColumn('column'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testUnnamedColumn()
    {
        $column = $this->getMockBuilder(ElementDescription::class)->disableOriginalConstructor()->getMock();
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

    public function testActions()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $action = new ElementDescription($type, ['option' => 'value']);

        $grid = new Grid([], ['action' => $action]);

        $this->assertTrue($grid->hasAction('action'));
        $this->assertSame($action, $grid->getAction('action'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testUnnamedAction()
    {
        $action = $this->getMockBuilder(ElementDescription::class)->disableOriginalConstructor()->getMock();
        $grid = new Grid([], [$action]);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidAction()
    {
        $grid = new Grid([], ['action' => 'invalid']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testActionNotFound()
    {
        $grid = new Grid();
        $this->assertFalse($grid->hasAction('action'));

        $grid->getAction('action');
    }

    public function testCreateView()
    {
        $options = ['option' => 'value'];

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $columnView = new ElementView('column', $type);

        $type->expects($this->exactly(2))
            ->method('createView')
            ->withConsecutive(
                ['column', $options],
                ['action', $options]
            )
            ->willReturn($columnView);

        $column = new ElementDescription($type, ['option' => 'value']);

        $grid = new Grid(['column' => $column], ['action' => $column]);

        $view = $grid->createView();

        $this->assertInstanceOf(GridView::class, $view);

        $this->assertEquals(1, count($view->columns));
        $this->assertTrue(isset($view->columns['column']));
        $this->assertSame($columnView, $view->columns['column']);

        $this->assertEquals(1, count($view->actions));
        $this->assertTrue(isset($view->actions['action']));
        $this->assertSame($columnView, $view->actions['action']);
    }
}
