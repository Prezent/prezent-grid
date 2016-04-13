<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base grid type extension
 *
 * @see GridType
 * @author Sander Marechal
 */
abstract class BaseGridTypeExtension implements GridTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(GridView $view, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getExtendedType();
}
