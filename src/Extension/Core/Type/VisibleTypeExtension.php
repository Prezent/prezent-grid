<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Make actions visible or hidden
 *
 * @see BaseElementTypeExtension
 * @author Sander Marechal
 */
class VisibleTypeExtension extends BaseElementTypeExtension
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * Constructor
     *
     * @param PropertyAccessorInterface $accessor
     */
    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['visible' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['visible'] = $options['visible'];
    }

    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        if (is_string($view->vars['visible'])) {
            $view->vars['visible'] = $this->accessor->getValue($item, $view->vars['visible']);
        } elseif (is_callable($view->vars['visible'])) {
            $view->vars['visible'] = call_user_func($view->vars['visible'], $item);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ActionType::class;
    }
}
