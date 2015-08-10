<?php

namespace Prezent\Grid\Extension\Core;

use Prezent\Grid\BaseGridExtension;
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
    protected function loadColumnTypes()
    {
        return [
            new Type\BooleanType(),
            new Type\ColumnType($this->accessor),
            new Type\DateTimeType(),
            new Type\StringType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function loadColumnTypeExtensions()
    {
        return [];
    }
}
