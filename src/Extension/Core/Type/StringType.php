<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;

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
    public function getName()
    {
        return 'string';
    }
}
