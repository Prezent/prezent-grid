<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Resolved element type
 *
 * @author Sander Marechal
 */
class ResolvedElementType
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
     * @var ResolvedElementType
     */
    private $parent = null;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * Constructor
     *
     * @param ElementType $innerType
     * @param ElementTypeExtension[] $typeExtensions
     * @param ResolvedElementType $parent
     */
    public function __construct(ElementType $innerType, array $typeExtensions = [], ResolvedElementType $parent = null)
    {
        foreach ($typeExtensions as $typeExtension) {
            if (!($typeExtension instanceof ElementTypeExtension)) {
                throw new UnexpectedTypeException(ElementTypeExtension::class, $typeExtension);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    /**
     * Get the template block prefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return $this->innerType->getBlockPrefix();
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
     * Get the inner element type
     *
     * @return ElementType
     */
    public function getInnerType()
    {
        return $this->innerType;
    }

    /**
     * Create the view for the element
     *
     * @param string $name
     * @param View $parent
     * @param array $options
     * @return View
     */
    public function createView($name, View $parent = null, array $options = [])
    {
        $view = new ElementView($name, $this, $parent);
        $options = $this->getOptionsResolver()->resolve($options);

        $this->buildView($view, $options);

        return $view;
    }

    /**
     * Build the view for the element
     *
     * @param ElementView $view
     * @param mixed $options
     * @return void
     */
    public function buildView(ElementView $view, array $options = [])
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
     * Bind an item to the view
     *
     * @param ElementView $view
     * @param mixed $item
     * @return void
     */
    public function bindView(ElementView $view, $item)
    {
        if ($this->parent) {
            $this->parent->bindView($view, $item);
        }

        $this->innerType->bindView($view, $item);

        foreach ($this->typeExtensions as $typeExtension) {
            $typeExtension->bindView($view, $item);
        }
    }

    /**
     * Get the optionsResolver
     *
     * @return OptionsResolver
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

            foreach ($this->typeExtensions as $typeExtension) {
                $typeExtension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }
}
