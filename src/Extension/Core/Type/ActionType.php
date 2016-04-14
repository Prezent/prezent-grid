<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;

/**
 * ActionType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class ActionType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return ElementType::class;
    }
}
