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
    public function bindView(ElementView $view, $item)
    {
        if (is_object($view->vars['value']) && !method_exists($view->vars['value'], '__toString')) {
            throw new InvalidArgumentException('Objects must implement __toString() in a string column type');
        }

        if (is_array($view->vars['value'])) {
            $view->vars['value'] = print_r($view->vars['value'], true);
        }

        $view->vars['value'] = (string) $view->vars['value'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'string';
    }
}
