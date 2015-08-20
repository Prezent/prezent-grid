<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Base column type extension
 *
 * @see ElementType
 * @author Sander Marechal
 */
abstract class BaseElementTypeExtension implements ElementTypeExtension
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
    abstract public function getExtendedType();
}
