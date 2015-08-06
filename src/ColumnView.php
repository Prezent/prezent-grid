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
     * Constructor
     *
     * @param string $name Column name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
