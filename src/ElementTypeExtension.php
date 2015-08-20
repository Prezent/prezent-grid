<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Interface for element type extensions
 *
 * @author Sander Marechal
 */
interface ElementTypeExtension
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
     * Get the element type name of the extended type
     *
     * @return string
     */
    public function getExtendedType();
}
