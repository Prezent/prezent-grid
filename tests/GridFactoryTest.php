<?php

namespace Prezent\Tests\Grid;

use Prezent\Grid\ElementTypeFactory;
use Prezent\Grid\GridTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Grid;
use Prezent\Grid\GridBuilder;
use Prezent\Grid\GridType;
use Prezent\Grid\ResolvedGridType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GridFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTypedBuilder()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($this->createGridTypeFactory($type), $elementTypeFactory);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder($type));
    }

    public function testCreateNamedBuilder()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($this->createGridTypeFactory($type), $elementTypeFactory);

        $this->assertInstanceOf(GridBuilder::class, $gridFactory->createBuilder('grid'));
    }

    public function testCreateGrid()
    {
        $type = $this->createType();

        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($this->createGridTypeFactory($type), $elementTypeFactory);

        $this->assertInstanceOf(Grid::class, $gridFactory->createGrid('grid'));
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testCreateWrongType()
    {
        $elementTypeFactory = $this->getMockBuilder(ElementTypeFactory::class)->getMock();
        $gridFactory = new DefaultGridFactory($this->createGridTypeFactory(), $elementTypeFactory);

        $gridFactory->createBuilder(new \stdClass());
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

    private function createGridTypeFactory($type = null)
    {
        $type = new ResolvedGridType($type ?: $this->getMockBuilder(GridType::class)->getMock());

        $factory = $this->getMockBuilder(GridTypeFactory::class)->getMock();
        $factory->method('getType')->willReturn($type);
        $factory->method('resolveType')->willReturn($type);

        return $factory;
    }
}
