<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * StringType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class BooleanType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'true_message'  => 'yes',
            'false_message' => 'no',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['true_message' ] = $options['true_message'];
        $view->vars['false_message'] = $options['false_message'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        $view->vars['value'] = $view->vars['value'] ? $view->vars['true_message'] : $view->vars['false_message'];
    }
}
