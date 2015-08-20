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
     * Check if the extension has a column type
     *
     * @param string $name
     * @return bool
     */
    public function hasElementType($name);

    /**
     * Get a column type
     *
     * @param string $name
     * @throws InvalidArgumentException
     * @return ElementType
     */
    public function getElementType($name);

    /**
     * Get column type extensions for a type
     *
     * @param string $name
     * @return ElementTypeExtension[]
     */
    public function getElementTypeExtensions($name);
}
