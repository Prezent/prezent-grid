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
class GridView
{
    /**
     * @var ViewCollection
     */
    public $columns = [];

    /**
     * Constructor
     *
     * @param array $columns
     */
    public function __construct(array $columns = [])
    {
        $this->columns = new ViewCollection($columns);
    }
}
