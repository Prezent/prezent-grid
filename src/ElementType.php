<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * Get the element value for this type
     *
     * @param ElementView $view
     * @param mixed $item Row item
     * @param mixed $value Value returned by the parent type
     * @return void
     */
    public function getValue(ElementView $view, $item, $value);

    /**
     * Get the element type name
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
