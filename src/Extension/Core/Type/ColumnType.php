<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnType;
use Prezent\Grid\ColumnView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function __construct(PropertyAccessor $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefault([
            'property_path' => null,
            'label'         => null,
        ]);
    }

    public function buildView(ColumnView $view, array $options)
    {
        $view->vars['property_path'] = $options['property_path'] ?: $view->name;
        $view->vars['label']         = $options['label'] !== false ? $options['label'] : $view->name;
    }

    public function getValue(ColumnView $view, $item)
    {
        return (string) $this->accessor->getValue($item, $view->vars['property_path']);
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
    }
}
