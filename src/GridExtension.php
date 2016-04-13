<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;

/**
 * Grid extension interface
 *
 * @author Sander Marechal
 */
interface GridExtension
{
    /**
     * Check if the extension has a grid type
     *
     * @param string $name
     * @return bool
     */
    public function hasGridType($name);

    /**
     * Get a grid type
     *
     * @param string $name
     * @throws InvalidArgumentException
     * @return GridType
     */
    public function getGridType($name);

    /**
     * Get grid type extensions for a grid type
     *
     * @param string $name
     * @return GridTypeExtension[]
     */
    public function getGridTypeExtensions($name);

    /**
     * Check if the extension has an element type
     *
     * @param string $name
     * @return bool
     */
    public function hasElementType($name);

    /**
     * Get an element type
     *
     * @param string $name
     * @throws InvalidArgumentException
     * @return ElementType
     */
    public function getElementType($name);

    /**
     * Get element type extensions for an element type
     *
     * @param string $name
     * @return ElementTypeExtension[]
     */
    public function getElementTypeExtensions($name);
}
