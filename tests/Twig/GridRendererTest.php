<?php

namespace Prezent\Grid\Tests\Twig;

use Prezent\Grid\ColumnView;
use Prezent\Grid\GridView;
use Prezent\Grid\ResolvedColumnType;
use Prezent\Grid\Twig\GridRenderer;

class GridRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderBlock()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid'));

        $renderer->renderBlock('grid', new GridView(), [], []);
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

    public function testInheritance()
    {
        $theme = $this->getMockBuilder(\Twig_Template::class)->disableOriginalConstructor()->getMock();
        $type = $this->getMockBuilder(ResolvedColumnType::class)->disableOriginalConstructor()->getMock();
        $renderer = new GridRenderer($theme);

        $view = new ColumnView('column', $type);
        $view->vars['block_types'] = ['string', 'column'];

        $theme->method('hasBlock')->will($this->returnCallback(function ($value) {
            return 'column_widget' === $value;
        }));

        $theme->expects($this->once())
            ->method('renderBlock')
            ->with($this->equalTo('grid_column_widget'));

        $renderer->renderBlock('grid_widget', $view, [], []);
    }
}
