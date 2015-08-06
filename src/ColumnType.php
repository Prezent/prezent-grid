<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Interface for column types
 *
 * @author Sander Marechal
 */
interface ColumnType
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
    public function buildView(ColumnView $view, array $options);

    /**
     * Get the column value for this type
     *
     * @param ColumnView $view
     * @param mixed $item Row item
     * @param mixed $value Value returned by the parent type
     * @return void
     */
    public function getValue(ColumnView $view, $item, $value);

    /**
     * Get the column type name
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
