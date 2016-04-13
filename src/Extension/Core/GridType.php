<?php

namespace Prezent\Grid\Extension\Core;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\GridView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Core grid type
 *
 * @see BaseGridType
 * @author Sander Marechal
 */
class GridType extends BaseGridType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('attr', [])
            ->setAllowedTypes('attr', 'array')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(GridView $view, array $options = [])
    {
        $view->vars['attr'] = $options['attr'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'grid';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
