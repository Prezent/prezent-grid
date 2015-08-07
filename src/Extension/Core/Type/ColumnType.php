<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * ColumnType
 *
 * @see ColumnType
 * @author Sander Marechal
 */
class ColumnType extends BaseColumnType
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
            'label'         => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        $view->vars['property_path'] = $options['property_path'] ?: $view->name;
        $view->vars['label']         = $options['label'] === null ? $view->name : $options['label'];
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(ColumnView $view, $item, $value)
    {
        return $this->accessor->getValue($item, $view->vars['property_path']);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'column';
    }
}
