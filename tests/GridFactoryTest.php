<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ColumnTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Grid;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\GridType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GridFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmptyBuilder()
    {
        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder());
    }

    public function testCreateTypedBuilder()
    {
        $type = $this->createType();

        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory);


        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder($type));
    }

    public function testCreateNamedBuilder()
    {
        $type = $this->createType();

        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory, ['grid' => $type]);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder('grid'));
    }

    public function testCreateGrid()
    {
        $type = $this->createType();

        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory, ['grid' => $type]);

        $this->assertInstanceOf(Grid::class, $gridFactory->createGrid('grid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testCreateWrongType()
    {
        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory);

        $gridFactory->createBuilder(new \stdClass());
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testCreateUnknownType()
    {
        $columnTypeFactory = $this->getMockBuilder(ColumnTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($columnTypeFactory);

        $gridFactory->createBuilder('unknown');
    }

    private function createType()
    {
        $type = $this->getMockBuilder(GridType::class)->getMock();

        $type->expects($this->once())
             ->method('configureOptions')
             ->with($this->isInstanceOf(OptionsResolverInterface::class));

        $type->expects($this->once())
             ->method('buildGrid')
             ->with($this->isInstanceOf(GridBuilder::class), $this->isType('array'));

        return $type;
    }
}
