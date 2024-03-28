<?php

namespace Prezent\Grid;

use Prezent\Grid\Extension\Core\GridType as CoreGridType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base grid type
 *
 * @see GridType
 * @author Sander Marechal
 */
abstract class BaseGridType implements GridType
{
    /**
     * {@inheritDoc}
     */
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(GridView $view, array $options = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(GridView $view, $item)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return CoreGridType::class;
    }
}
