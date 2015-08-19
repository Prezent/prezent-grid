<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ColumnType;
use Prezent\Grid\ColumnTypeFactory;
use Prezent\Grid\ColumnDescription;
use Prezent\Grid\Grid;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\ResolvedColumnType;

class GridBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromString()
    {
        $builder = new GridBuilder($this->createFactory());
        $column = $builder->createColumn('column', ['option' => 'value']);

        $this->assertInstanceOf(ColumnDescription::class, $column);
        $this->assertInstanceOf(ResolvedColumnType::class, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    public function testCreateFromType()
    {
        $type = $this->getMock(ColumnType::class);

        $builder = new GridBuilder($this->createFactory());
        $column = $builder->createColumn($type, ['option' => 'value']);

        $this->assertInstanceOf(ColumnDescription::class, $column);
        $this->assertInstanceOf(ResolvedColumnType::class, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    public function testCreateFromResolvedType()
    {
        $resolvedType = $this->getMockBuilder(ResolvedColumnType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $builder = new GridBuilder($this->createFactory());
        $column = $builder->createColumn($resolvedType, ['option' => 'value']);

        $this->assertInstanceOf(ColumnDescription::class, $column);
        $this->assertSame($resolvedType, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testCreateInvalid()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->createColumn(new \DateTime(), ['option' => 'value']);
    }

    public function testAdd()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->addColumn('column', 'string', ['option' => 'value']);

        $this->assertTrue($builder->hasColumn('column'));
        $this->assertInstanceOf(ColumnDescription::class, $builder->getColumn('column'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testAddInvalidColumnName()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->addColumn(new \DateTime(), 'string', ['option' => 'value']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetInvalidColumn()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->getColumn('invalid');
    }

    public function testGetGrid()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->addColumn('column', 'string', ['option' => 'value']);

        $grid = $builder->getGrid();

        $this->assertInstanceOf(Grid::class, $grid);
        $this->assertTrue($grid->hasColumn('column'));
        $this->assertInstanceOf(ColumnDescription::class, $grid->getColumn('column'));
    }

    private function createFactory()
    {
        $resolvedType = $this->getMockBuilder(ResolvedColumnType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory = $this->getMock(ColumnTypeFactory::class);
        $factory->expects($this->any())->method('getType')->willReturn($resolvedType);
        $factory->expects($this->any())->method('resolveType')->willReturn($resolvedType);

        return $factory;
    }
}
