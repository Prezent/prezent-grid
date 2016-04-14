<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolved grid type
 *
 * @author Sander Marechal
 */
class ResolvedGridType
{
    /**
     * @var ElementType
     */
    private $innerType;

    /**
     * @var array
     */
    private $typeExtensions = [];

    /**
     * @var ResolvedGridType
     */
    private $parent;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * Constructor
     *
     * @param GridType $innerType
     * @param GridTypeExtension[] $typeExtensions
     * @param ResolvedGridType $parent
     */
    public function __construct(GridType $innerType, array $typeExtensions = [], ResolvedGridType $parent = null)
    {
        foreach ($typeExtensions as $typeExtension) {
            if (!($typeExtension instanceof GridTypeExtension)) {
                throw new UnexpectedTypeException(GridTypeExtension::class, $typeExtension);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * Get the parent type
     *
     * @return ResolvedElementType
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Build the grid
     *
     * @param GridBuilder $builder
     * @param array $options
     * @return void
     */
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        if ($this->parent) {
            $this->parent->buildGrid($builder, $options);
        }

        $this->innerType->buildGrid($builder, $options);

        foreach ($this->typeExtensions as $typeExtension) {
            $typeExtension->buildGrid($builder, $options);
        }
    }

    /**
     * Build the view for the grid
     *
     * @param GridView $view
     * @param mixed $options
     * @return void
     */
    public function buildView(GridView $view, array $options = [])
    {
        if ($this->parent) {
            $this->parent->buildView($view, $options);
        }

        $this->innerType->buildView($view, $options);

        foreach ($this->typeExtensions as $typeExtension) {
            $typeExtension->buildView($view, $options);
        }
    }

    /**
     * Get the optionsResolver
     *
     * @return OptionsResolver
     */
    public function getOptionsResolver()
    {
        if (!$this->optionsResolver) {
            if ($this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);

            foreach ($this->typeExtensions as $typeExtension) {
                $typeExtension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }
}
