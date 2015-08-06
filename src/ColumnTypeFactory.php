<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidargumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Column type factory interface
 *
 * @author Sander Marechal
 */
interface ColumnTypeFactory
{
    /**
     * Get a type from the factory
     *
     * @param string $name
     * @return ResolvedColumnType
     *
     * @throws UnexpectedTypeException if $name is not a string
     * @throws InvalidArgumentException if the type cannot be found
     */
    public function getType($name);
}
