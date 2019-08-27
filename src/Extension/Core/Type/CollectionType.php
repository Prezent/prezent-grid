<?php

namespace Prezent\Grid\Extension\Core\Type;

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Exception\InvalidArgumentException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * CollectionType
 *
 * @see BaseElementType
 * @author Robert-Jan Bijl
 */
class CollectionType extends BaseElementType
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined([
                'item_max_count',
                'item_property_path',
                'item_separator',
            ])
            ->setAllowedTypes('item_max_count', ['boolean', 'integer'])
            ->setAllowedTypes('item_property_path', ['null', 'string', 'callable'])
            ->setAllowedTypes('item_separator', 'string')
            ->setDefaults([
                'item_max_count' => false,
                'item_separator' => ','
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['item_max_count'] = $options['item_max_count'];
        $view->vars['item_separator'] = $options['item_separator'];
        if (isset($options['item_property_path'])) {
            $view->vars['item_property_path'] = $options['item_property_path'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function bindView(ElementView $view, $item)
    {
        // convert the element to an array
        // @todo: should this be better?
        $value = $view->vars['value'];
        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new InvalidArgumentException(
                'Objects must either be an array, or implement Traversable in a collection column type'
            );
        }

        $collection = [];
        $nbElements = 0;
        foreach ($value as $element) {
            // if the element is an object, use the property path to fetch the correct field
            if (is_object($element)) {
                if (is_string($view->vars['item_property_path'])) {
                    $collection[]  = $this->accessor->getValue($element, $view->vars['item_property_path']);
                } elseif (is_callable($view->vars['item_property_path'])) {
                    $collection[] = call_user_func($view->vars['item_property_path'], $element);
                }
                // if the element is an array, use the property path to fetch the correct index
            } elseif (is_array($element)) {
                if (isset($element[$view->vars['item_property_path']])) {
                    $collection[] = $element[$view->vars['item_property_path']];
                }
                // otherwise, just use the element itself
            } else {
                $collection[] = $element;
            }

            // count the processed elements. If a limit is given, and we've reached it, let's just stop
            $nbElements += 1;
            if ($view->vars['item_max_count'] && $nbElements >= $view->vars['item_max_count']) {
                break;
            }
        }

        $view->vars['value'] = $collection;
    }
}
