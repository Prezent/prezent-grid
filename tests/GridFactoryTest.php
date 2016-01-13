<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ElementTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Grid;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\GridType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateEmptyBuilder()
    {
        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder());
    }

    public function testCreateTypedBuilder()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory);


        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder($type));
    }

    public function testCreateNamedBuilder()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory, ['grid' => $type]);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder('grid'));
    }

    public function testCreateGrid()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory, ['grid' => $type]);

        $this->assertInstanceOf(Grid::class, $gridFactory->createGrid('grid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testCreateWrongType()
    {
        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory);

        $gridFactory->createBuilder(new \stdClass());
    }

    /**
     * @expectedException Prezent\Grid\Exception\InvalidArgumentException
     */
    public function testCreateUnknownType()
    {
        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($elementTypeFactory);

        $gridFactory->createBuilder('unknown');
    }

    private function createType()
    {
        $type = $this->getMockBuilder(GridType::class)->getMock();

        $type->expects($this->once())
             ->method('configureOptions')
             ->with($this->isInstanceOf(OptionsResolver::class));

        $type->expects($this->once())
             ->method('buildGrid')
             ->with($this->isInstanceOf(GridBuilder::class), $this->isType('array'));

        return $type;
    }
}
