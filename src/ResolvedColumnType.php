<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\UnexpectedTypeException;
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
     * @var array
     */
    private $typeExtensions = [];

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
     * @param ColumnTypeExtension[] $typeExtensions
     * @param ResolvedColumnType $parent
     */
    public function __construct(ColumnType $innerType, array $typeExtensions = [], ResolvedColumnType $parent = null)
    {
        foreach ($typeExtensions as $typeExtension) {
            if (!($typeExtension instanceof ColumnTypeExtension)) {
                throw new UnexpectedTypeException(ColumnTypeExtension::class, $typeExtension);
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
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
        $view = new ColumnView($name, $this);
        $options = $this->getOptionsResolver()->resolve($options);

        $this->buildView($view, $options);

        return $view;
    }

    /**
     * Build the view for the column
     *
     * @param ColumnView $view
     * @param mixed $options
     * @return void
     */
    public function buildView(ColumnView $view, array $options = [])
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
     * Get the column value from an item
     *
     * @param ColumnView $view
     * @param mixed $item
     * @return mixed
     */
    public function getValue(ColumnView $view, $item)
    {
        $value = null;

        if ($this->parent) {
            $value = $this->parent->getValue($view, $item, $value);
        }

        return $this->innerType->getValue($view, $item, $value);
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

            foreach ($this->typeExtensions as $typeExtension) {
                $typeExtension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }
}
