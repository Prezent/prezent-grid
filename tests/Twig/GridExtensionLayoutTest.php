<?php

namespace Prezent\Tests\Grid\Twig;

use Prezent\Grid\GridView;
use Prezent\Grid\Tests\PHPUnit\GridFactoryProvider;
use Prezent\Grid\Tests\PHPUnit\TwigProvider;

class GridExtensionLayoutTest extends \PHPUnit_Framework_TestCase
{
    use GridFactoryProvider, TwigProvider;

    private $view;

    public function testRenderGridHeaderWidget()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_widget', $this->view['foo']);
        $this->assertMatchesXpath('[contains(., "foo")]', $output);
    }

    public function testRenderGridHeaderColumn()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_column', $this->view['foo']);
        $this->assertMatchesXpath('/th[contains(., "foo")]', $output);
    }

    public function testRenderGridHeaderRow()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_row', $this->view);
        $this->assertMatchesXpath('/tr/th', $output);
    }

    public function testRenderGridWidget()
    {
        $output = $this->twig->renderer->renderBlock('grid_widget', $this->view['foo'], ['foo' => 'bar']);
        $this->assertMatchesXpath('[contains(., "bar")]', $output);
    }

    public function testRenderGridColumn()
    {
        $output = $this->twig->renderer->renderBlock('grid_column', $this->view['foo'], ['foo' => 'bar']);
        $this->assertMatchesXpath('/td[contains(., "bar")]', $output);
    }

    public function testRenderGridRow()
    {
        $output = $this->twig->renderer->renderBlock('grid_row', $this->view, [['foo' => 'bar']]);
        $this->assertMatchesXpath('/tr/td', $output);
    }

    public function testRenderGrid()
    {
        $output = $this->twig->renderer->renderBlock('grid', $this->view, [['foo' => 'bar']]);

        $this->assertMatchesXpath('/table/thead/tr', $output);
        $this->assertMatchesXpath('/table/tbody/tr', $output);
    }

    protected function setUp()
    {
        $this->setUpTwig();
        $this->setUpGridFactory();

        $grid = $this->gridFactory->createBuilder()
            ->add('foo', 'string', ['property_path' => '[foo]'])
            ->getGrid();

        $this->view = $grid->createView();
    }
}
