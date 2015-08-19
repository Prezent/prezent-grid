<?php

namespace Prezent\Grid\Extension\Core\ColumnType;

use Prezent\Grid\BaseColumnType;

/**
 * ActionType
 *
 * @see BaseColumnType
 * @author Sander Marechal
 */
class ActionType extends BaseColumnType
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'action';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return null;
    }
}
