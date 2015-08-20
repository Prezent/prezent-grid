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
    public function offsetExists($name)
    {
        return isset($this->views[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($name)
    {
        return $this->views[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($name, $value)
    {
        throw new \BadMethodCallException('Not supported');
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($name)
    {
        throw new \BadMethodCallException('Not supported');
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->views);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->views);
    }
}
