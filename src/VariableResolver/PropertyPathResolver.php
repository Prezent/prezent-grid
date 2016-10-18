<?php

namespace Prezent\Grid\VariableResolver;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\VariableResolver;
use Symfony\Component\PropertyAccess\Exception\ExceptionInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Resolve property paths in strings
 *
 * Property paths are specified between braces {}, for example:
 *
 *     "/controller/{name}/{vars[foo]}"
 *
 * @author Sander Marechal
 */
class PropertyPathResolver implements VariableResolver
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
    public function resolve($variable, $item)
    {
        if (!is_string($variable)) {
            return $variable;
        }

        $variable = preg_replace_callback('/(?<!\\\\)\\{((?:[^\\}]|\\\\})+)\\}/', function ($match) use ($item) {
            try {
                return $this->accessor->getValue($item, $match[1]);
            } catch (ExceptionInterface $e) {
                throw new InvalidArgumentException(sprintf('Invalid property path "%s"', $match[1]), null, $e);
            }
        }, $variable);

        return str_replace(['\\{', '\\}'], ['{', '}'], $variable);
    }
}
