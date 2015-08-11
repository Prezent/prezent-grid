<?php

namespace Prezent\Grid;

/**
 * Resolve variables using an item
 *
 * @author Sander Marechal
 */
interface VariableResolver
{
    /**
     * Resolve a variable with an item
     *
     * @param mixed $variable
     * @param mixed $item
     * @return mixed The resolved variable
     */
    public function resolve($variable, $item);
}
