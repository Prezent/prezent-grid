<?php

namespace Prezent\Tests\Grid\Twig;

use Prezent\Grid\Grid;
use Prezent\Grid\Tests\PHPUnit\MatchesXpath;
use Prezent\Grid\Twig\GridExtension;
use Prezent\Grid\Twig\GridRenderer;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Tests\Extension\Fixtures\StubTranslator;

class GridExtensionLayoutTest extends \PHPUnit_Framework_TestCase
{
    private $extension;

    public function testRenderGrid()
    {
        $output = $this->extension->renderer->renderBlock('grid', new Grid(), [['foo' => 'bar']]);
        $this->assertMatchesXpath('/table/thead/tr', $output);
        $this->assertMatchesXpath('/table/tbody/tr', $output);
    }

    protected function setUp()
    {
        parent::setUp();

        $renderer = new GridRenderer('grid.html.twig');
        $this->extension = new GridExtension($renderer);
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../src/Resources/views/Grid');
        
        $environment = new \Twig_Environment($loader, array('strict_variables' => true));
        $environment->addExtension(new TranslationExtension(new StubTranslator()));
        $environment->addExtension($this->extension);

        $this->extension->initRuntime($environment);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->extension = null;
    }

    public static function assertMatchesXpath($expression, $html, $count = 1, $message = '')
    {
        self::assertThat($html, self::matchesXpath($expression, $count), $message);
    }

    public static function matchesXpath($expression, $count = 1)
    {
        return new MatchesXpath($expression, $count);
    }
}
