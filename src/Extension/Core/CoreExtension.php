<?php

namespace Prezent\Grid\Extension\Core;

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\VariableResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Core extension providing the base types
 *
 * @see GridExtension
 * @author Sander Marechal
 */
class CoreExtension extends BaseGridExtension
{
    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * @var VariableResolver
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param PropertyAccessorInterface $accessor
     * @param VariableResolver $resolver
     */
    public function __construct(PropertyAccessorInterface $accessor, VariableResolver $resolver)
    {
        $this->accessor = $accessor;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadGridTypes()
    {
        return [
            new GridType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function loadElementTypes()
    {
        return [
            new Type\ActionType(),
            new Type\BooleanType(),
            new Type\CollectionType($this->accessor),
            new Type\ColumnType($this->accessor),
            new Type\DateTimeType(),
            new Type\ElementType($this->resolver),
            new Type\StringType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function loadElementTypeExtensions()
    {
        return [
            new Type\TruncateTypeExtension(),
            new Type\UrlTypeExtension($this->resolver),
            new Type\VisibleTypeExtension($this->accessor),
        ];
    }
}
