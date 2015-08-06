<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Interface for column type extensions
 *
 * @author Sander Marechal
 */
interface ColumnTypeExtension
{
    /**
     * Configure options for this type
     *
     * @param OptionsResolverInterface $resolver
     * @return void
     */
    public function configureOptions(OptionsResolverInterface $resolver);

    /**
     * Set up the view for this type
     *
     * @param ColumnView $view
     * @param array $options
     * @return void
     */
    public function createView(ColumnView $view, array $options);

    /**
     * Get the column type name of the extended type
     *
     * @return string
     */
    public function getExtendedType();
}
