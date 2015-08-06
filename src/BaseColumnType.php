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
    public function createView(ColumnView $view, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
