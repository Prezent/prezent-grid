<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Default column type factory
 *
 * @see ColumnTypeFactory
 * @author Sander Marechal
 */
class DefaultColumnTypeFactory implements ColumnTypeFactory
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * Constructor
     *
     * @param GridExtension[]
     *
     * @throws UnexpectedTypeException if an invalid extension is passed
     */
    public function __construct(array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!($extension instanceof GridExtension)) {
                throw new UnexpectedTypeException(GridExtension::class, $extension);
            }
        }

        $this->extensions = $extensions;
    }

    /**
     * {@inheritDoc}
     */
    public function getType($name)
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException('string', $name);
        }

        if (!isset($this->types[$name])) {
            $type = null;
            $typeExtensions = [];

            foreach ($this->extensions as $extension) {
                if ($extension->hasColumnType($name)) {
                    $type = $extension->getColumnType($name);
                    break;
                }
            }

            if (!$type) {
                throw new InvalidArgumentException(sprintf('Could not load column type "%s"', $name));
            }

            $this->types[$name] = $this->resolveType($type);
        }

        return $this->types[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function resolveType(ColumnType $type)
    {
        $name = $type->getName();
        $typeExtensions = [];
        $parentType = $type->getParent();

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge($typeExtensions, $extension->getColumnTypeExtensions($name));
        }

        if ($parentType instanceof ColumnType) {
            $parentType = $this->resolveType($parentType);
        } elseif ($parentType !== null) {
            $parentType = $this->getType($parentType);
        }

        return new ResolvedColumnType($type, $typeExtensions, $parentType);
    }
}
