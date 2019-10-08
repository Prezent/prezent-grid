<?php

namespace Prezent\Grid\Twig\Tests\TokenParser;

use Prezent\Grid\Twig\TokenParser\GridThemeTokenParser;
use Prezent\Grid\Twig\Node\GridThemeNode;

class GridThemeTokenParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getTestsForGridTheme
     */
    public function testCompile($source, $expected)
    {
        $env = new \Twig_Environment($this->createMock('Twig_LoaderInterface'), array('cache' => false, 'autoescape' => false, 'optimizations' => 0));
        $env->addTokenParser(new GridThemeTokenParser());
        $source = new \Twig_Source($source, 'template.html.twig');
        $stream = $env->tokenize($source);
        $parser = new \Twig_Parser($env);

        $expected->setTemplateName('template.html.twig');
        $expected->setSourceContext($source);

        $this->assertEquals($expected, $parser->parse($stream)->getNode('body')->getNode(0));
    }

    public function getTestsForGridTheme()
    {
        return array(
            array(
                '{% grid_theme grid "tpl1" %}',
                new GridThemeNode(
                    new \Twig_Node_Expression_Name('grid', 1),
                    new \Twig_Node_Expression_Array(array(
                        new \Twig_Node_Expression_Constant(0, 1),
                        new \Twig_Node_Expression_Constant('tpl1', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid "tpl1" "tpl2" %}',
                new GridThemeNode(
                    new \Twig_Node_Expression_Name('grid', 1),
                    new \Twig_Node_Expression_Array(array(
                        new \Twig_Node_Expression_Constant(0, 1),
                        new \Twig_Node_Expression_Constant('tpl1', 1),
                        new \Twig_Node_Expression_Constant(1, 1),
                        new \Twig_Node_Expression_Constant('tpl2', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with "tpl1" %}',
                new GridThemeNode(
                    new \Twig_Node_Expression_Name('grid', 1),
                    new \Twig_Node_Expression_Constant('tpl1', 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with ["tpl1"] %}',
                new GridThemeNode(
                    new \Twig_Node_Expression_Name('grid', 1),
                    new \Twig_Node_Expression_Array(array(
                        new \Twig_Node_Expression_Constant(0, 1),
                        new \Twig_Node_Expression_Constant('tpl1', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
            array(
                '{% grid_theme grid with ["tpl1", "tpl2"] %}',
                new GridThemeNode(
                    new \Twig_Node_Expression_Name('grid', 1),
                    new \Twig_Node_Expression_Array(array(
                        new \Twig_Node_Expression_Constant(0, 1),
                        new \Twig_Node_Expression_Constant('tpl1', 1),
                        new \Twig_Node_Expression_Constant(1, 1),
                        new \Twig_Node_Expression_Constant('tpl2', 1),
                    ), 1),
                    1,
                    'grid_theme'
                ),
            ),
        );
    }
}
