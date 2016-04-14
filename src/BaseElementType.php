<?php

namespace Prezent\Grid;

use Prezent\Grid\Extension\Core\Type\ColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base element type
 *
 * @see ElementType
 * @author Sander Marechal
 */
abstract class BaseElementType implements ElementType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        // Taken from Symfony\Component\Form\Util\StringUtil
        // Non-greedy ("+?") to match "type" suffix, if present
        if (preg_match('~([^\\\\]+?)(type)?$~i', get_class($this), $matches)) {
            return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1_\\2', '\\1_\\2'], $matches[1]));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return ColumnType::class;
    }
}
