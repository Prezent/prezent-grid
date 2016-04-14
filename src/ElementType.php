<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface for element types
 *
 * @author Sander Marechal
 */
interface ElementType
{
    /**
     * Configure options for this type
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Set up the view for this type
     *
     * @param ElementView $view
     * @param array $options
     * @return void
     */
    public function buildView(ElementView $view, array $options);

    /**
     * Bind an item to the view
     *
     * @param ElementView $view
     * @param mixed $item
     * @return void
     */
    public function bindView(ElementView $view, $item);

    /**
     * Get the prefix of the template block name
     *
     * @return string
     */
    public function getBlockPrefix();

    /**
     * Get the parent type name
     *
     * @return string
     */
    public function getParent();
}
