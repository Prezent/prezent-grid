<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;

/**
 * StringType
 *
 * @see BaseColumnType
 * @author Sander Marechal
 */
class BooleanType extends BaseColumnType
{
    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item, $value)
    {
        return $value ? 'yes' : 'no';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'boolean';
    }
}
