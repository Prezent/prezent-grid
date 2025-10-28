<?php

namespace Prezent\Grid;

/**
 * Element description
 *
 * @author Sander Marechal
 */
class ElementDescription
{
    /**
     * @var ResolvedElementType
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor
     *
     * @param ResolvedElementType $type
     * @param array $options
     */
    public function __construct(ResolvedElementType $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * Create an element view
     *
     * @param mixed $name
     * @param ?View $parent
     * @return View
     */
    public function createView($name, ?View $parent = null)
    {
        return $this->type->createView($name, $parent, $this->options);
    }

    /**
     * Getter for type
     *
     * @return ResolvedElementType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Getter for options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
