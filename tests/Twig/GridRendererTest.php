<?php

namespace Prezent\Grid\Tests\Twig;

use Prezent\Grid\ElementView;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedElementType;
use Prezent\Grid\Twig\GridRenderer;

class GridRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultTheme()
    {
        $renderer = new GridRenderer();

        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);

        $environment = $this->createMock(\Twig_Environment::class);
        $environment->expects($this->once())
            ->method('loadTemplate')
            ->willReturn($theme);

        $renderer->setEnvironment($environment);
    }

    public function testRenderBlock()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);
        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid'));

        $renderer = new GridRenderer([$theme]);
        $renderer->setEnvironment($this->createMock(\Twig_Environment::class));

        $renderer->renderBlock('grid', new GridView(), [], []);
    }

    public function testVariables()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $renderer = new GridRenderer([$theme]);
        $renderer->setEnvironment($this->createMock(\Twig_Environment::class));

        $view = new ElementView('column', $type);
        $view->vars['foo'] = 'bar';
        $view->vars['baz'] = 'quu';

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with(
                $this->equalTo('grid_header_column'),
                $this->callback(function ($value) {
                    return $value['foo'] === 'overridden' && $value['baz'] === 'quu';
                })
            );

        $renderer->renderBlock('grid_header_column', $view, [], ['foo' => 'overridden']);
    }

    public function testVariableInheritance()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $renderer = new GridRenderer([$theme]);
        $renderer->setEnvironment($this->createMock(\Twig_Environment::class));

        $view = new ElementView('column', $type);
        $view->vars['foo'] = 'bar';
        $view->vars['baz'] = 'quu';

        $theme->expects($this->exactly(2))
            ->method('renderBlock')
            ->with(
                $this->anything(),
                $this->callback(function ($value) {
                    return $value['foo'] === 'overridden' && $value['baz'] === 'quu';
                })
            )->will($this->onConsecutiveCalls(
                // Simulate a nested renderBlock()
                $this->returnCallback(function () use ($renderer, $view) {
                    $renderer->renderBlock('grid_widget', $view, [], []);
                }),
                null
            ));

        $renderer->renderBlock('grid_column', $view, [], ['foo' => 'overridden']);
    }

    public function testWidgetInheritance()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn(['grid_column_widget' => []]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $renderer = new GridRenderer([$theme]);
        $renderer->setEnvironment($this->createMock(\Twig_Environment::class));

        $view = new ElementView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_column_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }

    public function testThemeInheritance()
    {
        $parent = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $parent->method('getBlocks')->willReturn(['grid_column_widget' => []]);

        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn(['grid_string_widget' => []]);
        $theme->method('getParent')->willReturn($parent);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $renderer = new GridRenderer([$theme]);
        $renderer->setEnvironment($this->createMock(\Twig_Environment::class));

        $view = new ElementView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_string_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }
}
