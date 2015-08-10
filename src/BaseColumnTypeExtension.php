<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base column type extension
 *
 * @see ColumnType
 * @author Sander Marechal
 */
abstract class BaseColumnTypeExtension implements ColumnTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getExtendedType();
}
