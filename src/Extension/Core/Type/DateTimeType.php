<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * DateTime column
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class DateTimeType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['locale']      = $options['locale'];
        $view->vars['date_format'] = $options['date_format'];
        $view->vars['time_format'] = $options['time_format'];
        $view->vars['pattern']     = $options['pattern'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        if (!$view->vars['value']) {
            return;
        }

        if (!($view->vars['value'] instanceof \DateTimeInterface)) {
            throw new UnexpectedTypeException('DateTimeInterface', $view->vars['value']);
        }

        $formatter = \IntlDateFormatter::create(
            $view->vars['locale'],
            $view->vars['date_format'],
            $view->vars['time_format'],
            \IntlTimeZone::createDefault(),
            \IntlDateFormatter::GREGORIAN,
            $view->vars['pattern']
        );

        $view->vars['value'] = $formatter->format($view->vars['value']);
    }
}
