<?php

namespace Prezent\Grid\Twig\Tests\TokenParser;

use Prezent\Grid\Twig\TokenParser\GridThemeTokenParser;
use Prezent\Grid\Twig\Node\GridThemeNode;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Parser;
use Twig\Source;

class GridThemeTokenParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getTestsForGridTheme
     */
    public function testCompile($source, $expected)
    {
        $env = new Environment($this->createMock(LoaderInterface::class), array('cache' => false, 'autoescape' => false, 'optimizations' => 0));
        $env->addTokenParser(new GridThemeTokenParser());
        $source = new Source($source, 'template.html.twig');
        $stream = $env->tokenize($source);
        $parser = new Parser($env);

        $expected->setSourceContext($source);

        $this->assertEquals($expected, $parser->parse($stream)->getNode('body')->getNode(0));
    }

    public function getTestsForGridTheme()
    {
        return array(
            array(
                '{% grid_theme grid "tpl1" %}',
                new GridThemeNode(
                    new NameExpression('grid', 1),
                    new ArrayExpression(array(
                        new ConstantExpression(0, 1),
                        new ConstantExpression('tpl1', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid "tpl1" "tpl2" %}',
                new GridThemeNode(
                    new NameExpression('grid', 1),
                    new ArrayExpression(array(
                        new ConstantExpression(0, 1),
                        new ConstantExpression('tpl1', 1),
                        new ConstantExpression(1, 1),
                        new ConstantExpression('tpl2', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with "tpl1" %}',
                new GridThemeNode(
                    new NameExpression('grid', 1),
                    new ConstantExpression('tpl1', 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with ["tpl1"] %}',
                new GridThemeNode(
                    new NameExpression('grid', 1),
                    new ArrayExpression(array(
                        new ConstantExpression(0, 1),
                        new ConstantExpression('tpl1', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with ["tpl1", "tpl2"] %}',
                new GridThemeNode(
                    new NameExpression('grid', 1),
                    new ArrayExpression(array(
                        new ConstantExpression(0, 1),
                        new ConstantExpression('tpl1', 1),
                        new ConstantExpression(1, 1),
                        new ConstantExpression('tpl2', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
        );
    }
}
