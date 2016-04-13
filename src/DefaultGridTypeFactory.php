<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Default grid type factory
 *
 * @see GridTypeFactory
 * @author Sander Marechal
 */
class DefaultGridTypeFactory implements GridTypeFactory
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * Constructor
     *
     * @param GridExtension[] $extensions
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

            foreach ($this->extensions as $extension) {
                if ($extension->hasGridType($name)) {
                    $type = $extension->getGridType($name);
                    break;
                }
            }

            if (!$type) {
                throw new InvalidArgumentException(sprintf('Could not load grid type "%s"', $name));
            }

            $this->types[$name] = $this->resolveType($type);
        }

        return $this->types[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function resolveType(GridType $type)
    {
        $name = $type->getName();
        $typeExtensions = [];
        $parentType = $type->getParent();

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge($typeExtensions, $extension->getGridTypeExtensions($name));
        }

        if ($parentType instanceof GridType) {
            $parentType = $this->resolveType($parentType);
        } elseif ($parentType !== null) {
            $parentType = $this->getType($parentType);
        }

        return new ResolvedGridType($type, $typeExtensions, $parentType);
    }
}
