<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\DefaultColumnTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ColumnTypeTest extends \PHPUnit_Framework_TestCase
{
    private $columnTypeFactory;
    private $gridFactory;

    public function testDefaultsFromName()
    {
        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'column')
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('foo', $view['foo']->vars['label']);
        $this->assertEquals('foo', $view['foo']->vars['property_path']);
    }

    public function testEmptyLabel()
    {
        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'column', ['label' => false])
            ->getGrid();

        $view = $grid->createView();

        $this->assertFalse($view['foo']->vars['label']);
    }

    public function testGetValue()
    {
        $data = new \stdClass();
        $data->one = 'col1';
        $data->other = 'col2';

        $grid = $this->gridFactory->createBuilder()
            ->add('one', 'column')
            ->add('two', 'column', ['property_path' => 'other'])
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals('col1', $view['one']->getValue($data));
        $this->assertEquals('col2', $view['two']->getValue($data));
    }

    public function setUp()
    {
        $this->columnTypeFactory = new DefaultColumnTypeFactory([
            new CoreExtension(PropertyAccess::createPropertyAccessor()),
        ]);

        $this->gridFactory = new DefaultGridFactory($this->columnTypeFactory);
    }
}
