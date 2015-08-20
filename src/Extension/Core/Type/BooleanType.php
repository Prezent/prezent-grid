<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;

/**
 * StringType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class BooleanType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function getValue(ElementView $view, $item, $value)
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
