<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\ColumnType;
use Prezent\Grid\ColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Prezent\Grid\ResolvedColumnType;

class ResolvedColumnTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $type = $this->getMock(ColumnType::class);
        $extension = $this->getMock(ColumnTypeExtension::class);
        $parent = $this->getMockBuilder(ResolvedColumnType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resolvedType = new ResolvedColumnType($type, [$extension], $parent);

        $this->assertInstanceOf(ResolvedColumnType::class, $resolvedType);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidConstruction()
    {
        $type = $this->getMock(ColumnType::class);
        $resolvedType = new ResolvedColumnType($type, ['invalid']);
    }

    public function testCreateView()
    {
        $type = $this->createType(ColumnType::class, ['option']);
        $resolvedType = new ResolvedColumnType($type);

        $options = ['option' => 'value'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ColumnView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    public function testCreateParentView()
    {
        $parent = new ResolvedColumnType($this->createType(ColumnType::class, ['parent']));
        $type = $this->createType(ColumnType::class, ['option']);
        $resolvedType = new ResolvedColumnType($type, [], $parent);

        $options = ['option' => 'value', 'parent' => 'parent'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ColumnView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    public function testCreateViewExtension()
    {
        $type = $this->createType(ColumnType::class, ['option']);
        $extension = $this->createType(ColumnTypeExtension::class, ['extension']);
        $resolvedType = new ResolvedColumnType($type, [$extension]);

        $options = ['option' => 'value', 'extension' => 'ext'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ColumnView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    private function createType($class, array $required = [])
    {
        $type = $this->getMock($class);

        $type->expects($this->once())
            ->method('configureOptions')
            ->will($this->returnCallback(function ($resolver) use ($required) {
                $resolver->setRequired($required);
            }));

        $type->expects($this->once())
            ->method('buildView')
            ->will($this->returnCallback(function ($view, $options) use ($required) {
                foreach ($required as $option) {
                    $view->vars[$option] = $options[$option];
                }
            }));

        return $type;
    }
}
