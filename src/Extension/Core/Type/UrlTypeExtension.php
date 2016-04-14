<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Make columns linkable
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class UrlTypeExtension extends BaseElementTypeExtension
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
        $resolver->setDefined(['url']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        if (isset($options['url'])) {
            $view->vars['url'] = $options['url'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        if (!isset($view->vars['url'])) {
            return;
        }

        $view->vars['url'] = $this->resolver->resolve($view->vars['url'], $item);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ElementType::class;
    }
}
