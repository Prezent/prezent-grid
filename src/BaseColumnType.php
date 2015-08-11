<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base column type
 *
 * @see ColumnType
 * @author Sander Marechal
 */
abstract class BaseColumnType implements ColumnType
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
    public function bindView(ColumnView $view, $item)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item, $value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getName();

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'column';
    }
}
