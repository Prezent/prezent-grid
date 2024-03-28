<?php

namespace Prezent\Grid\Extension\Core;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\GridView;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Core grid type
 *
 * @see BaseGridType
 * @author Sander Marechal
 */
class GridType extends BaseGridType
{
    /**
     * @var VariableResolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param VariableResolver $resolver
     */
    public function __construct(VariableResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'attr' => [],
                'row_attr' => [],
            ])
            ->setAllowedTypes('attr', 'array')
            ->setAllowedTypes('row_attr', 'array')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(GridView $view, array $options = [])
    {
        $view->vars['attr'] = $options['attr'];
        $view->vars['row_attr'] = $options['row_attr'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(GridView $view, $item)
    {
        foreach ($view->vars['row_attr'] as $key => $value) {
            $view->vars['row_attr'][$key] = $this->resolver->resolve($value, $item);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
