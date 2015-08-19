<?php

namespace Prezent\Tests\Grid\Twig;

use Prezent\Grid\GridView;
use Prezent\Grid\Tests\PHPUnit\GridFactoryProvider;
use Prezent\Grid\Tests\PHPUnit\TwigProvider;

class GridExtensionLayoutTest extends \PHPUnit_Framework_TestCase
{
    use GridFactoryProvider, TwigProvider;

    public function testRenderGridHeaderWidget()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_widget', $this->createView()->columns['foo']);
        $this->assertMatchesXpath('[contains(., "foo")]', $output);
    }

    public function testRenderGridHeaderColumn()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_column', $this->createView()->columns['foo']);
        $this->assertMatchesXpath('/th[contains(., "foo")]', $output);
    }

    public function testRenderGridHeaderRow()
    {
        $output = $this->twig->renderer->renderBlock('grid_header_row', $this->createView());
        $this->assertMatchesXpath('/tr/th', $output, 2);
    }

    public function testRenderGridWidget()
    {
        $output = $this->twig->renderer->renderBlock('grid_widget', $this->createView()->columns['foo'], ['foo' => 'bar']);
        $this->assertMatchesXpath('[contains(., "bar")]', $output);
    }

    public function testRenderGridColumn()
    {
        $output = $this->twig->renderer->renderBlock('grid_column', $this->createView()->columns['foo'], ['foo' => 'bar']);
        $this->assertMatchesXpath('/td[contains(., "bar")]', $output);
    }

    public function testRenderGridAction()
    {
        $output = $this->twig->renderer->renderBlock('grid_action', $this->createView()->actions['edit'], ['foo' => 'bar']);
        $this->assertMatchesXpath('/a[contains(., "edit")]', $output);
    }

    public function testRenderGridActions()
    {
        $output = $this->twig->renderer->renderBlock('grid_actions', $this->createView(), ['foo' => 'bar']);
        $this->assertMatchesXpath('/td/a[contains(., "edit")]', $output);
    }

    public function testRenderGridRow()
    {
        $output = $this->twig->renderer->renderBlock('grid_row', $this->createView(), [['foo' => 'bar']]);
        $this->assertMatchesXpath('/tr/td', $output, 2);
    }

    public function testRenderGrid()
    {
        $output = $this->twig->renderer->renderBlock('grid', $this->createView(), [['foo' => 'bar']]);

        $this->assertMatchesXpath('/table/thead/tr', $output);
        $this->assertMatchesXpath('/table/tbody/tr', $output);
    }

    public function testRenderUrl()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string', [
                'property_path' => '[foo]',
                'url' => '/get/{[foo]}'
            ])
            ->getGrid();

        $view = $grid->createView();

        $output = $this->twig->renderer->renderBlock('grid_widget', $view->columns['foo'], ['foo' => 'bar']);
        $this->assertMatchesXpath('/a[@href="/get/bar"][contains(., "bar")]', $output);
    }

    protected function setUp()
    {
        $this->setUpTwig();
        $this->setUpGridFactory();

    }

    public function createView()
    {
        $grid = $this->gridFactory->createBuilder()
            ->addColumn('foo', 'string', ['property_path' => '[foo]'])
            ->addAction('edit', ['url' => '/edit/foo'])
            ->getGrid();

        return $grid->createView();
    }
}
