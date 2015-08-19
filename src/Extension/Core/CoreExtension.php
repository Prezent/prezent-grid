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
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\ActionType(),
            new ColumnType\BooleanType(),
            new ColumnType\ColumnType($this->accessor),
            new ColumnType\DateTimeType(),
            new ColumnType\ElementType(),
            new ColumnType\StringType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypeExtensions()
    {
        return [
            new ColumnType\UrlTypeExtension($this->resolver),
        ];
    }
}
