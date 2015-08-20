<?php

namespace Prezent\Grid\Tests;

use Prezent\Grid\ElementType;
use Prezent\Grid\ElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\ResolvedElementType;

class ResolvedElementTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        $type = $this->getMock(ElementType::class);
        $extension = $this->getMock(ElementTypeExtension::class);
        $parent = $this->getMockBuilder(ResolvedElementType::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resolvedType = new ResolvedElementType($type, [$extension], $parent);

        $this->assertInstanceOf(ResolvedElementType::class, $resolvedType);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidConstruction()
    {
        $type = $this->getMock(ElementType::class);
        $resolvedType = new ResolvedElementType($type, ['invalid']);
    }

    public function testCreateView()
    {
        $type = $this->createType(ElementType::class, ['option']);
        $resolvedType = new ResolvedElementType($type);

        $options = ['option' => 'value'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ElementView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    public function testCreateParentView()
    {
        $parent = new ResolvedElementType($this->createType(ElementType::class, ['parent']));
        $type = $this->createType(ElementType::class, ['option']);
        $resolvedType = new ResolvedElementType($type, [], $parent);

        $options = ['option' => 'value', 'parent' => 'parent'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ElementView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    public function testCreateViewExtension()
    {
        $type = $this->createType(ElementType::class, ['option']);
        $extension = $this->createType(ElementTypeExtension::class, ['extension']);
        $resolvedType = new ResolvedElementType($type, [$extension]);

        $options = ['option' => 'value', 'extension' => 'ext'];
        $view = $resolvedType->createView('column', $options);

        $this->assertInstanceOf(ElementView::class, $view);
        $this->assertEquals('column', $view->name);
        $this->assertEquals($options, $view->vars);
    }

    public function testGetValue()
    {
        $parentType = $this->createType(ElementType::class, ['parent']);
        $parentType->expects($this->once())
            ->method('getValue')
            ->willReturn('value');

        $parent = new ResolvedElementType($parentType);

        $type = $this->createType(ElementType::class, ['option']);
        $type->expects($this->once())
            ->method('getValue')
            ->with($this->isInstanceOf(ElementView::class), $this->anything(), $this->equalTo('value'))
            ->willReturn('value-added');

        $resolvedType = new ResolvedElementType($type, [], $parent);

        $options = ['option' => 'value', 'parent' => 'parent'];
        $view = $resolvedType->createView('column', $options);

        $this->assertEquals('value-added', $resolvedType->getValue($view, []));
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
