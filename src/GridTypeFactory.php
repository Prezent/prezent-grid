<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidargumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Grid type factory interface
 *
 * @author Sander Marechal
 */
interface GridTypeFactory
{
    /**
     * Get a type from the factory
     *
     * @param string $name
     * @return ResolvedGridType
     *
     * @throws UnexpectedTypeException if $name is not a string
     * @throws InvalidArgumentException if the type cannot be found
     */
    public function getType($name);

    /**
     * Resolve a grid type
     *
     * @param GridType $type
     * @return ResolvedGridType
     */
    public function resolveType(GridType $type);
}
