<?php

namespace Prezent\Grid\Extension\Core\ColumnType;

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
        $blockTypes = [];
        for ($type = $view->getType(); $type != null; $type = $type->getParent()) {
            $blockTypes[] = $type->getName();
        }

        $view->vars['block_types']   = $blockTypes;
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

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
