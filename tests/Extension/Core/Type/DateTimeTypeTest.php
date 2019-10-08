<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\Extension\Core\Type\DateTimeType;
use Prezent\Grid\Tests\Extension\Core\TypeTest;

class DateTimeTypeTest extends TypeTest
{
    /**
     * @dataProvider optionsProvider
     */
    public function testOptions($options, $expected)
    {
        $data = (object)['date' => new \DateTime('2015-01-01 12:00:00')];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('date', DateTimeType::class, $options)
            ->getGrid();

        $view = $grid->createView();
        $view->columns['date']->bind($data);

        $this->assertEquals($expected, $view->columns['date']->vars['value']);
    }

    public function testNotDateTime()
    {
        $this->expectException(UnexpectedTypeException::class);

        $data = (object)['date' => '2000-01-01'];

        $grid = $this->gridFactory->createBuilder()
            ->addColumn('date', DateTimeType::class)
            ->getGrid();

        $view = $grid->createView();
        $view->columns['date']->bind($data);
    }

    public function optionsProvider()
    {
        return [
            [
                ['locale' => 'en_US'],
                'Jan 1, 2015, 12:00:00 PM',
            ],
            [
                [
                    'locale'      => 'en_US',
                    'date_format' => \IntlDateFormatter::SHORT,
                    'time_format' => \IntlDateFormatter::SHORT,
                ],
                '1/1/15, 12:00 PM',
            ],
            [
                [
                    'locale'  => 'en_US',
                    'pattern' => 'yyyy qqq',
                ],
                '2015 Q1',
            ],
            [
                ['locale' => 'nl_NL'],
                '1 jan. 2015 12:00:00',
            ],
        ];
    }
}
