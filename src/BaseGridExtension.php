<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Base grid extension
 *
 * @see GridExtension
 * @author Sander Marechal
 */
abstract class BaseGridExtension implements GridExtension
{
    /**
     * @var ElementType[]
     */
    private $elementTypes = null;

    /**
     * @var ElementTypeExtension[]
     */
    private $elementTypeExtensions = null;

    /**
     * {@inheritDoc}
     */
    public function hasElementType($name)
    {
        if (null == $this->elementTypes) {
            $this->initElementTypes();
        }

        return isset($this->elementTypes[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementType($name)
    {
        if (!$this->hasElementType($name)) {
            throw new InvalidArgumentException(sprintf('The column type "%s" cannot be loaded', $name));
        }

        return $this->elementTypes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function getElementTypeExtensions($name)
    {
        if (null == $this->elementTypeExtensions) {
            $this->initElementTypeExtensions();
        }

        if (!isset($this->elementTypeExtensions[$name])) {
            return [];
        }

        return $this->elementTypeExtensions[$name];
    }

    /**
     * Get element types
     *
     * @return array
     */
    protected function loadElementTypes()
    {
        return [];
    }

    /**
     * Get element type extensions
     *
     * @return array
     */
    protected function loadElementTypeExtensions()
    {
        return [];
    }

    /**
     * Initialize element types
     *
     * @return void
     */
    private function initElementTypes()
    {
        $this->elementTypes = [];

        foreach ($this->loadElementTypes() as $elementType) {
            if (!$elementType instanceof ElementType) {
                throw new UnexpectedTypeException(ElementType::class, $elementType);
            }

            $this->elementTypes[$elementType->getName()] = $elementType;
        }
    }

    /**
     * Initialize element type extensions
     *
     * @return void
     */
    private function initElementTypeExtensions()
    {
        $this->elementTypeExtensions = [];

        foreach ($this->loadElementTypeExtensions() as $elementTypeExtension) {
            if (!$elementTypeExtension instanceof ElementTypeExtension) {
                throw new UnexpectedTypeException(ElementTypeExtension::class, $elementTypeExtension);
            }

            $this->elementTypeExtensions[$elementTypeExtension->getExtendedType()][] = $elementTypeExtension;
        }
    }
}
