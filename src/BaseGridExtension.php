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
     * @var GridType[]
     */
    private $gridTypes = null;

    /**
     * @var GridTypeExtension[]
     */
    private $gridTypeExtensions = null;

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
    public function hasGridType($name)
    {
        if (null == $this->gridTypes) {
            $this->initGridTypes();
        }

        return isset($this->gridTypes[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getGridType($name)
    {
        if (!$this->hasGridType($name)) {
            throw new InvalidArgumentException(sprintf('The grid type "%s" cannot be loaded', $name));
        }

        return $this->gridTypes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function getGridTypeExtensions($name)
    {
        if (null == $this->gridTypeExtensions) {
            $this->initGridTypeExtensions();
        }

        if (!isset($this->gridTypeExtensions[$name])) {
            return [];
        }

        return $this->gridTypeExtensions[$name];
    }

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
     * Get grid types
     *
     * @return array
     */
    protected function loadGridTypes()
    {
        return [];
    }

    /**
     * Get grid type extensions
     *
     * @return array
     */
    protected function loadGridTypeExtensions()
    {
        return [];
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
     * Initialize grid types
     *
     * @return void
     */
    private function initGridTypes()
    {
        $this->gridTypes = [];

        foreach ($this->loadGridTypes() as $gridType) {
            if (!$gridType instanceof GridType) {
                throw new UnexpectedTypeException(GridType::class, $gridType);
            }

            $this->gridTypes[get_class($gridType)] = $gridType;
        }
    }

    /**
     * Initialize grid type extensions
     *
     * @return void
     */
    private function initGridTypeExtensions()
    {
        $this->gridTypeExtensions = [];

        foreach ($this->loadGridTypeExtensions() as $gridTypeExtension) {
            if (!$gridTypeExtension instanceof GridTypeExtension) {
                throw new UnexpectedTypeException(GridTypeExtension::class, $gridTypeExtension);
            }

            $this->gridTypeExtensions[$gridTypeExtension->getExtendedType()][] = $gridTypeExtension;
        }
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

            $this->elementTypes[get_class($elementType)] = $elementType;
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
