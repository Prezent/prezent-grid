<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidargumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Element type factory interface
 *
 * @author Sander Marechal
 */
interface ElementTypeFactory
{
    /**
     * Get a type from the factory
     *
     * @param string $name
     * @return ResolvedElementType
     *
     * @throws UnexpectedTypeException if $name is not a string
     * @throws InvalidArgumentException if the type cannot be found
     */
    public function getType($name);

    /**
     * Resolve an element type
     *
     * @param ElementType $type
     * @return ResolvedElementType
     */
    public function resolveType(ElementType $type);
}
