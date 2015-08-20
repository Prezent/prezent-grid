<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Exception\InvalidArgumentException;

/**
 * StringType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class StringType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function getValue(ElementView $view, $item, $value)
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
