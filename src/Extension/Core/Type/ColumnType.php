<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * ColumnType
 *
 * @see ElementType
 * @author Sander Marechal
 */
class ColumnType extends BaseElementType
{
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * Constructor
     *
     * @param PropertyAccess $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'property_path' => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['property_path'] = $options['property_path'] === null ? $view->name : $options['property_path'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        $view->vars['value'] = null;

        if ($view->vars['property_path']) {
            $view->vars['value'] = $this->accessor->getValue($item, $view->vars['property_path']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'column';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return 'element';
    }
}
