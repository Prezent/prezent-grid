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
    protected function getTypes()
    {
        return [
            new ColumnType($this->accessor),
            new StringType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getTypeExtensions()
    {
        return [];
    }
}
