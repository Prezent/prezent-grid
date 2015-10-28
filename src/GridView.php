<?php

namespace Prezent\Grid;

/**
 * Grid view
 *
 * @author Sander Marechal
 */
class GridView implements View
{
    /**
     * @var ViewCollection
     */
    public $columns = [];

    /**
     * @var ViewCollection
     */
    public $actions = [];

    /**
     * Constructor
     *
     * @param array $columns
     * @param array $actions
     */
    public function __construct(array $columns = [], array $actions = [])
    {
        $this->columns = new ViewCollection($columns);
        $this->actions = new ViewCollection($actions);
    }
}
