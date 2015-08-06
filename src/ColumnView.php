<?php

namespace Prezent\Grid;

/**
 * Column view
 *
 * @author Sander Marechal
 */
class ColumnView
{
    /**
     * @var string
     * @readonly
     */
    public $name;

    /**
     * @var array
     */
    public $vars;

    /**
     * @var ColumnType
     */
    private $type;

    /**
     * Constructor
     *
     * @param string $name Column name
     */
    public function __construct($name, ColumnType $type)
    {
        $this->name = $name;
        $this->type = $type;
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
}
