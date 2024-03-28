<?php

namespace Prezent\Grid\Tests\Twig;

use Prezent\Grid\ElementView;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedElementType;
use Prezent\Grid\ResolvedGridType;
use Prezent\Grid\Twig\GridRenderer;
use Twig\Environment;
use Twig\Template;

class GridRendererTest extends \PHPUnit\Framework\TestCase
{
    public function testRenderBlock()
    {
        $theme = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);
        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid'));

        $environment = $this->createMock(Environment::class);
        $environment->method('mergeGlobals')->will($this->returnArgument(0));

        $type = $this->getMockBuilder(ResolvedGridType::class)->disableOriginalConstructor()->getMock();

        $renderer = new GridRenderer([$theme], $environment);
        $renderer->renderBlock('grid', new GridView($type), [], []);
    }

    public function testVariables()
    {
        $theme = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $environment = $this->createMock(Environment::class);
        $environment->method('mergeGlobals')->will($this->returnArgument(0));

        $renderer = new GridRenderer([$theme], $environment);

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
        $theme = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn([]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $environment = $this->createMock(Environment::class);
        $environment->method('mergeGlobals')->will($this->returnArgument(0));

        $renderer = new GridRenderer([$theme], $environment);

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
        $theme = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn(['grid_column_widget' => []]);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $environment = $this->createMock(Environment::class);
        $environment->method('mergeGlobals')->will($this->returnArgument(0));

        $renderer = new GridRenderer([$theme], $environment);

        $view = new ElementView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_column_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }

    public function testThemeInheritance()
    {
        $parent = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $parent->method('getBlocks')->willReturn(['grid_column_widget' => []]);

        $theme = $this->getMockBuilder(Template::class)->disableOriginalConstructor()->getMock();
        $theme->method('getBlocks')->willReturn(['grid_string_widget' => []]);
        $theme->method('getParent')->willReturn($parent);

        $type = $this->getMockBuilder(ResolvedElementType::class)->disableOriginalConstructor()->getMock();

        $environment = $this->createMock(Environment::class);
        $environment->method('mergeGlobals')->will($this->returnArgument(0));

        $renderer = new GridRenderer([$theme], $environment);

        $view = new ElementView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_string_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }
}
