<?php

namespace Prezent\Grid\VariableResolver;

use Prezent\Grid\Exception\UnexpectedTypeException;
use Prezent\Grid\VariableResolver;

/**
 * Chain multiple resolvers
 *
 * @author Sander Marechal
 */
class ChainResolver implements VariableResolver
{
    /**
     * @var VariableResolver[]
     */
    private $resolvers = [];

    /**
     * Constructor
     *
     * @param array $resolvers
     */
    public function __construct(array $resolvers)
    {
        foreach ($resolvers as $resolver) {
            if (!($resolver instanceof VariableResolver)) {
                throw new UnexpectedTypeException(VariableResolver::class, $resolver);
            }
        }

        $this->resolvers = $resolvers;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($variable, $item)
    {
        foreach ($this->resolvers as $resolver) {
            $variable = $resolver->resolve($variable, $item);
        }

        return $variable;
    }
}
