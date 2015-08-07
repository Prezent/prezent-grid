<?php

namespace Prezent\Grid\Tests\Extension\Core\Type;

class DateTimeTypeTest extends TypeTest
{
    /**
     * @dataProvider optionsProvider
     */
    public function testOptions($options, $expected)
    {
        $data = (object)['date' => new \DateTime('2015-01-01 12:00:00')];

        $grid = $this->gridFactory->createBuilder()
            ->add('date', 'datetime', $options)
            ->getGrid();

        $view = $grid->createView();

        $this->assertEquals($expected, $view['date']->getValue($data));
    }

    public function optionsProvider()
    {
        return [
            [
                ['locale' => 'en_US'],
                'Jan 1, 2015 12:00:00 PM',
            ],
            [
                [
                    'locale'      => 'en_US',
                    'date_format' => \IntlDateFormatter::SHORT,
                    'time_format' => \IntlDateFormatter::SHORT,
                ],
                '1/1/15 12:00 PM',
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
