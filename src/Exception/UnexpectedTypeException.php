<?php

namespace Prezent\Grid\Exception;

/**
 * Unexpected type exception
 *
 * @see InvalidArgumentException
 * @author Sander Marechal
 */
class UnexpectedTypeException extends InvalidArgumentException
{
    /**
     * Constructor
     *
     * @param mixed $value
     * @param string $expectedType
     */
    public function __construct($expectedType, $value)
    {
        parent::__construct(sprintf(
            'Expected argument of type "%s", "%s" given',
            $expectedType,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }
}
