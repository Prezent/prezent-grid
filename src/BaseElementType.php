<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base element type
 *
 * @see ElementType
 * @author Sander Marechal
 */
abstract class BaseElementType implements ElementType
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
    public function buildView(ElementView $view, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
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
