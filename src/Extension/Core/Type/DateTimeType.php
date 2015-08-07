<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * DateTimeType
 *
 * @see BaseColumnType
 * @author Sander Marechal
 */
class DateTimeType extends BaseColumnType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'locale'      => \Locale::getDefault(),
            'date_format' => \IntlDateFormatter::MEDIUM,
            'time_format' => \IntlDateFormatter::MEDIUM,
            'pattern'     => '',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        $view->vars['locale']      = $options['locale'];
        $view->vars['date_format'] = $options['date_format'];
        $view->vars['time_format'] = $options['time_format'];
        $view->vars['pattern']     = $options['pattern'];
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item, $value)
    {
        if (!($value instanceof \DateTimeInterface)) {
            throw new UnexpectedTypeException('DateTimeInterface', $value);
        }

        $formatter = \IntlDateFormatter::create(
            $view->vars['locale'],
            $view->vars['date_format'],
            $view->vars['time_format'],
            \IntlTimeZone::createDefault(),
            \IntlDateFormatter::GREGORIAN,
            $view->vars['pattern']
        );

        return $formatter->format($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'datetime';
    }
}
