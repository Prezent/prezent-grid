<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseColumnTypeExtension;
use Prezent\Grid\ColumnView;
use Prezent\Grid\VariableResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Make columns linkable
 *
 * @see BaseColumnTypeExtension
 * @author Sander Marechal
 */
class UrlTypeExtension extends BaseColumnTypeExtension
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
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefined(['url']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ColumnView $view, array $options)
    {
        if (isset($options['url'])) {
            $view->vars['url'] = $options['url'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ColumnView $view, $item)
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
        return 'column';
    }
}
