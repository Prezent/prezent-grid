<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Immutable view collection
 *
 * @see \ArrayAccess
 * @see \IteratorAggregate
 * @see \Countable
 * @author Sander Marechal
 */
class ViewCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $views = [];

    /**
     * Constructor
     *
     * @param array $views
     */
    public function __construct(array $views = [])
    {
        foreach ($views as $view) {
            if (!($view instanceof View)) {
                throw new UnexpectedTypeException(View::class, $view);
            }
        }

        $this->views = $views;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($name): bool
    {
        return isset($this->views[$name]);
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->views[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($name, $view): void
    {
        if (!($view instanceof View)) {
            throw new UnexpectedTypeException(View::class, $view);
        }

        $this->views[$name] = $view;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($name): void
    {
        unset($this->views[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->views);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->views);
    }
}
