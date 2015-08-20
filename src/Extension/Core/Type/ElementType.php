<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * ElementType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class ElementType extends BaseElementType
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
    public function buildView(ElementView $view, array $options)
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
