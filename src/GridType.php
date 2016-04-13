<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Grid type
 *
 * @author Sander Marechal
 */
interface GridType
{
    /**
     * Build the grid
     *
     * @param GridBuilder $builder
     * @param array $options
     * @return void
     */
    public function buildGrid(GridBuilder $builder, array $options = []);

    /**
     * Build the grid view
     *
     * @param GridView $view
     * @param array $options
     * @return void
     */
    public function buildView(GridView $view, array $options = []);

    /**
     * Configure options for this grid type
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Get the grid type name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the parent type
     *
     * @return string
     */
    public function getParent();
}
