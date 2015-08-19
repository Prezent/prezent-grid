<?php

namespace Prezent\Grid\Extension\Core\ColumnType;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;
use Prezent\Grid\Exception\InvalidArgumentException;

/**
 * StringType
 *
 * @see BaseColumnType
 * @author Sander Marechal
 */
class StringType extends BaseColumnType
{
    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item, $value)
    {
        if (is_object($value) && !method_exists($value, '__toString')) {
            throw new InvalidArgumentException('Objects must implement __toString() in a string column type');
        }

        if (is_array($value)) {
            $value = print_r($value, true);
        }

        return (string) $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'string';
    }
}
