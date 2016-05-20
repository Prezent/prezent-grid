<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Default element type factory
 *
 * @see ElementTypeFactory
 * @author Sander Marechal
 */
class DefaultElementTypeFactory implements ElementTypeFactory
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
                if ($extension->hasElementType($name)) {
                    $type = $extension->getElementType($name);
                    break;
                }
            }

            if (!$type) {
                // Support fully-qualified class names
                if (class_exists($name) && in_array('Prezent\Grid\ElementType', class_implements($name))) {
                    $type = new $name();
                } else {
                    throw new InvalidArgumentException(sprintf('Could not load element type "%s"', $name));
                }
            }

            $this->types[$name] = $this->resolveType($type);
        }

        return $this->types[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function resolveType(ElementType $type)
    {
        $typeExtensions = [];
        $parentType = $type->getParent();

        foreach ($this->extensions as $extension) {
            $typeExtensions = array_merge($typeExtensions, $extension->getElementTypeExtensions(get_class($type)));
        }

        if ($parentType instanceof ElementType) {
            $parentType = $this->resolveType($parentType);
        } elseif ($parentType !== null) {
            $parentType = $this->getType($parentType);
        }

        return new ResolvedElementType($type, $typeExtensions, $parentType);
    }
}
