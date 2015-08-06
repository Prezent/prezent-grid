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
        $column = $builder->create('column', ['option' => 'value']);

        $this->assertInstanceOf(ColumnDescription::class, $column);
        $this->assertInstanceOf(ResolvedColumnType::class, $column->getType());
        $this->assertEquals(['option' => 'value'], $column->getOptions());
    }

    public function testCreateFromType()
    {
        $type = $this->getMock(ColumnType::class);

        $builder = new GridBuilder($this->createFactory());
        $column = $builder->create($type, ['option' => 'value']);

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
        $column = $builder->create($resolvedType, ['option' => 'value']);

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
        $builder->create(new \DateTime(), ['option' => 'value']);
    }

    public function testAdd()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->add('column', 'string', ['option' => 'value']);

        $this->assertTrue($builder->has('column'));
        $this->assertInstanceOf(ColumnDescription::class, $builder->get('column'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testAddInvalidColumnName()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->add(new \DateTime(), 'string', ['option' => 'value']);
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testGetInvalidColumn()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->get('invalid');
    }

    public function testGetGrid()
    {
        $builder = new GridBuilder($this->createFactory());
        $builder->add('column', 'string', ['option' => 'value']);

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
