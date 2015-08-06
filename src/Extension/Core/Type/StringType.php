<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * StringType
 *
 * @see ColumnType
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

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'column';
    }
}
