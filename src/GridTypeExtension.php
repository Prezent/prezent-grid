<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface for grid type extensions
 *
 * @author Sander Marechal
 */
interface GridTypeExtension
{
    /**
     * Configure options for this type
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Build the grid
     *
     * @param GridBuilder $builder
     * @param array $options
     * @return void
     */
    public function buildGrid(GridBuilder $builder, array $options = []);

    /**
     * Set up the view for this type
     *
     * @param GridView $view
     * @param array $options
     * @return void
     */
    public function buildView(GridView $view, array $options);

    /**
     * Get the grid type name of the extended type
     *
     * @return string
     */
    public function getExtendedType();
}
