<?php

namespace Prezent\Grid\Tests\Twig;

use Prezent\Grid\ColumnView;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedColumnType;
use Prezent\Grid\Twig\GridRenderer;

class GridRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultTheme()
    {
        $renderer = new GridRenderer(null);

        $environment = $this->getMock(\Twig_Environment::class);
        $environment->expects($this->once())
            ->method('loadTemplate');

        $renderer->setEnvironment($environment);
    }

    public function testRenderBlock()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid'));

        $renderer->renderBlock('grid', new GridView(), [], []);
    }

    /**
     * @expectedException Prezent\Grid\Exception\UnexpectedTypeException
     */
    public function testInvalidView()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $renderer->renderBlock('grid', new \stdClass(), [], []);
    }

    public function testVariables()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $view = new ColumnView('column', $type);
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
        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $view = new ColumnView('column', $type);
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
        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $view = new ColumnView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->method('hasBlock')->will($this->returnCallback(function ($value) {
            return 'grid_column_widget' === $value;
        }));

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_column_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }
}
