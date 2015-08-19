<?php

namespace Prezent\Grid\Extension\Core\ColumnType;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * ElementType
 *
 * @see BaseColumnType
 * @author Sander Marechal
 */
class ElementType extends BaseColumnType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'label' => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        $blockTypes = [];
        for ($type = $view->getType(); $type != null; $type = $type->getParent()) {
            $blockTypes[] = $type->getName();
        }

        $view->vars['block_types'] = $blockTypes;
        $view->vars['label']       = $options['label'] === null ? $view->name : $options['label'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'element';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
