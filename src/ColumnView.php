<?php

namespace Prezent\Grid;

/**
 * Column view
 *
 * @author Sander Marechal
 */
class ColumnView implements View
{
    /**
     * @var string
     * @readonly
     */
    public $name;

    /**
     * @var array
     */
    public $vars = [];

    /**
     * @var ResolvedColumnType
     */
    private $type;

    /**
     * Constructor
     *
     * @param string $name Column name
     */
    public function __construct($name, ResolvedColumnType $type)
    {
        $this->name = $name;
        $this->type = $type;
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

    /**
     * Get the value for a column
     *
     * @param mixed $item
     * @return mixed
     */
    public function getValue($item)
    {
        return $this->type->getValue($this, $item);
    }

    /**
     * Get the column type
     *
     * @return ResolvedColumnType
     */
    public function getType()
    {
        return $this->type;
    }
}
