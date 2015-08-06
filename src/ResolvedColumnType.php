<?php

namespace Prezent\Grid;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Resolved column type
 *
 * @author Sander Marechal
 */
class ResolvedColumnType
{
    /**
     * @var ColumnType
     */
    private $innerType;

    /**
     * @var ResolvedColumnType
     */
    private $parent = null;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * Constructor
     *
     * @param ColumnType $innerType
     * @param ResolvedColumnType $parent
     */
    public function __construct(ColumnType $innerType, ResolvedColumnType $parent = null)
    {
        $this->innerType = $innerType;
        $this->parent = $parent;
    }

    /**
     * Create the view for the column
     *
     * @param string $name
     * @param array $options
     * @return void
     */
    public function createView($name, array $options = [])
    {
        $view = new ColumnView($name);
        $options = $this->getOptionsResolver()->resolve($options);

        if ($this->parent) {
            $this->parent->createView($view, $options);
        }

        $this->innerType->createView($view, $options);

        return $view;
    }

    /**
     * Get the optionsResolver
     *
     * @return OptionsResolverInterface
     */
    private function getOptionsResolver()
    {
        if (!$this->optionsResolver) {
            if ($this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);
        }

        return $this->optionsResolver();
    }
}
