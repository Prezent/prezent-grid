<?php

namespace Prezent\Grid\Tests\PHPUnit;

use Prezent\Grid\Tests\PHPUnit\MatchesXpath;
use Prezent\Grid\Twig\GridExtension;
use Prezent\Grid\Twig\GridRenderer;

trait TwigProvider
{
    private $twig;

    private function setUpTwig()
    {
        $renderer = new GridRenderer(['grid.html.twig']);
        $this->twig = new GridExtension($renderer);
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../src/Resources/views/Grid');
        
        $environment = new \Twig_Environment($loader, array('strict_variables' => true));
        $environment->addExtension($this->twig);
        $environment->addExtension(new \Twig_Extensions_Extension_Text());

        $this->twig->initRuntime($environment);
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
