<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ElementType
 *
 * @see BaseElementType
 * @author Sander Marechal
 */
class ElementType extends BaseElementType
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
                'label' => null,
                'attr'  => [],
            ])
            ->setAllowedTypes('attr', 'array')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $blockTypes = [];
        for ($type = $view->getType(); $type != null; $type = $type->getParent()) {
            $blockTypes[] = $type->getBlockPrefix();
        }

        $view->vars['block_types'] = $blockTypes;
        $view->vars['label']       = $options['label'] === null ? $view->name : $options['label'];
        $view->vars['attr']        = $options['attr'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        foreach ($view->vars['attr'] as $key => $value) {
            $view->vars['attr'][$key] = $this->resolver->resolve($value, $item);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
    }
}
