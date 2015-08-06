<?php

namespace Prezent\Grid;

/**
 * Grid view
 *
 * @see \ArrayAccess
 * @see \IteratorAggregate
 * @see \Countable
 * @author Sander Marechal
 */
class GridView implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var ColumnView[]
     */
    private $columns = [];

    /**
     * Constructor
     *
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($name)
    {
        return $this->columns[$name];
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
        return new \ArrayIterator($this->columns);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->columns);
    }
}
