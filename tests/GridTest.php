<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ElementDescription;
use Prezent\Grid\ElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\Grid;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedElementType;
use Prezent\Grid\ResolvedGridType;

class GridTest extends \PHPUnit\Framework\TestCase
{
    public function testColumns()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $column = new ElementDescription($type, ['option' => 'value']);

        $grid = $this->createGrid(['column' => $column]);

        $this->assertTrue($grid->hasColumn('column'));
        $this->assertSame($column, $grid->getColumn('column'));
    }

    public function testUnnamedColumn()
    {
        $this->expectException(UnexpectedTypeException::class);

        $column = $this->getMockBuilder(ElementDescription::class)->disableOriginalConstructor()->getMock();
        $grid = $this->createGrid([$column]);
    }

    public function testInvalidColumn()
    {
        $this->expectException(UnexpectedTypeException::class);

        $grid = $this->createGrid(['column' => 'invalid']);
    }

    public function testColumnNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        $grid = $this->createGrid();
        $this->assertFalse($grid->hasColumn('column'));

        $grid->getColumn('column');
    }

    public function testRemoveColumn()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $column = new ElementDescription($type, ['option' => 'value']);

        $grid = $this->createGrid(['column' => $column]);

        $this->assertTrue($grid->hasColumn('column'));

        $grid->removeColumn('column');
        $this->assertFalse($grid->hasColumn('column'));

        $grid->removeColumn('does-not-exist'); // No exception
    }

    public function testActions()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $action = new ElementDescription($type, ['option' => 'value']);

        $grid = $this->createGrid([], ['action' => $action]);

        $this->assertTrue($grid->hasAction('action'));
        $this->assertSame($action, $grid->getAction('action'));
    }

    public function testUnnamedAction()
    {
        $this->expectException(UnexpectedTypeException::class);

        $action = $this->getMockBuilder(ElementDescription::class)->disableOriginalConstructor()->getMock();
        $grid = $this->createGrid([], [$action]);
    }

    public function testInvalidAction()
    {
        $this->expectException(UnexpectedTypeException::class);

        $grid = $this->createGrid([], ['action' => 'invalid']);
    }

    public function testActionNotFound()
    {
        $this->expectException(InvalidArgumentException::class);

        $grid = $this->createGrid();
        $this->assertFalse($grid->hasAction('action'));

        $grid->getAction('action');
    }

    public function testRemoveAction()
    {
        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();
        $action = new ElementDescription($type, ['option' => 'value']);

        $grid = $this->createGrid([], ['action' => $action]);

        $this->assertTrue($grid->hasAction('action'));

        $grid->removeAction('action');
        $this->assertFalse($grid->hasAction('action'));

        $grid->removeAction('does-not-exist'); // No exception
    }

    public function testCreateView()
    {
        $options = ['option' => 'value'];

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $columnView = new ElementView('column', $type);

        $type->expects($this->exactly(2))
            ->method('createView')
            ->withConsecutive(
                ['column', $this->isInstanceOf(GridView::class), $options],
                ['action', $this->isInstanceOf(GridView::class), $options]
            )
            ->willReturn($columnView);

        $column = new ElementDescription($type, ['option' => 'value']);

        $grid = $this->createGrid(['column' => $column], ['action' => $column]);

        $view = $grid->createView();

        $this->assertInstanceOf(GridView::class, $view);

        $this->assertEquals(1, count($view->columns));
        $this->assertTrue(isset($view->columns['column']));
        $this->assertSame($columnView, $view->columns['column']);

        $this->assertEquals(1, count($view->actions));
        $this->assertTrue(isset($view->actions['action']));
        $this->assertSame($columnView, $view->actions['action']);
    }

    private function createGrid($columns = [], $actions = [])
    {
        $type = $this->getMockBuilder(ResolvedGridType::class)->disableOriginalConstructor()->getMock();

        return new Grid($type, $columns, $actions);
    }
}
