<?php

namespace Prezent\Grid\VariableResolver;

use Prezent\Grid\VariableResolver;

/**
 * Resolve callback variables
 *
 * @author Sander Marechal
 */
class CallbackResolver implements VariableResolver
{
    /**
     * {@inheritDoc}
     */
    public function resolve($variable, $item)
    {
        if (!is_callable($variable)) {
            return $variable;
        }

        return call_user_func($variable, $item);
    }
}
