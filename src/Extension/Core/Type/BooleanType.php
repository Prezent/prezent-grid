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
    public function bindView(ElementView $view, $item)
    {
        $view->vars['value'] = $view->vars['value'] ? 'yes' : 'no';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'boolean';
    }
}
