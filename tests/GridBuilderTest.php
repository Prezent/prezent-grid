<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeFactory;
use Prezent\Grid\ElementDescription;
use Prezent\Grid\Grid;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\ResolvedElementType;
use Prezent\Grid\ResolvedGridType;

class GridBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromString()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $column = $builder->createColumn('column', ['option' => 'value']);

        $this->assertInstanceOf(ElementDescription::class, $column);
        $this->assertInstanceOf(ResolvedElementType::class, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    public function testCreateFromType()
    {
        $type = $this->getMock(ElementType::class);

        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $column = $builder->createColumn($type, ['option' => 'value']);

        $this->assertInstanceOf(ElementDescription::class, $column);
        $this->assertInstanceOf(ResolvedElementType::class, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    public function testCreateFromResolvedType()
    {
        $resolvedType = $this->getMockBuilder(ResolvedElementType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $column = $builder->createColumn($resolvedType, ['option' => 'value']);

        $this->assertInstanceOf(ElementDescription::class, $column);
        $this->assertSame($resolvedType, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testCreateInvalid()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->createColumn(new \DateTime(), ['option' => 'value']);
    }

    public function testAdd()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->addColumn('column', 'string', ['option' => 'value']);

        $this->assertTrue($builder->hasColumn('column'));
        $this->assertInstanceOf(ElementDescription::class, $builder->getColumn('column'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testAddInvalidColumnName()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->addColumn(new \DateTime(), 'string', ['option' => 'value']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetInvalidColumn()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->getColumn('invalid');
    }

    public function testAddAction()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->addAction('action', ['option' => 'value']);

        $this->assertTrue($builder->hasAction('action'));
        $this->assertInstanceOf(ElementDescription::class, $builder->getAction('action'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetInvalidAction()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->getAction('invalid');
    }

    public function testGetGrid()
    {
        $builder = new GridBuilder($this->createType(), $this->createFactory());
        $builder->addColumn('column', 'string', ['option' => 'value']);

        $grid = $builder->getGrid();

        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertTrue($grid->hasColumn('column'));
        $this->assertInstanceOf(ElementDescription::class, $grid->getColumn('column'));
    }

    private function createType()
    {
        return $this->getMockBuilder(ResolvedGridType::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function createFactory()
    {
        $resolvedType = $this->getMockBuilder(ResolvedElementType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = $this->getMock(ElementTypeFactory::class);
        $factory->expects($this->any())->method('getType')->willReturn($resolvedType);
        $factory->expects($this->any())->method('resolveType')->willReturn($resolvedType);

        return $factory;
    }
}
