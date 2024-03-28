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
     * @var ResolvedGridType
     */
    private $type;

    /**
     * @var ViewCollection
     */
    public $columns = [];

    /**
     * @var ViewCollection
     */
    public $actions = [];

    /**
     * @var array
     */
    public $vars = [];

    /**
     * Constructor
     *
     * @param array $columns
     * @param array $actions
     */
    public function __construct(ResolvedGridType $type, array $columns = [], array $actions = [])
    {
        $this->type = $type;
        $this->columns = new ViewCollection($columns);
        $this->actions = new ViewCollection($actions);
    }

    /**
     * Bind an item to the view
     *
     * @param mixed $item
     * @return void
     */
    public function bind($item)
    {
        $this->type->bindView($this, $item);
    }
}
