<?php

namespace Prezent\Grid;

/**
 * Column view
 *
 * @author Sander Marechal
 */
class ElementView implements View
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
     * @var View
     */
    public $parent;

    /**
     * @var ResolvedElementType
     */
    private $type;

    /**
     * Constructor
     *
     * @param string $name Element name
     * @param ResolvedElementType $type
     */
    public function __construct($name, ResolvedElementType $type, View $parent = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->parent = $parent;
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
     * Get the element type
     *
     * @return ResolvedElementType
     */
    public function getType()
    {
        return $this->type;
    }
}
