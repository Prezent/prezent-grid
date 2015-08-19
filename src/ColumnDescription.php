<?php

namespace Prezent\Grid;

/**
 * Column description
 *
 * @author Sander Marechal
 */
class ColumnDescription
{
    /**
     * @var ResolvedColumnType
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor
     *
     * @param ResolvedColumnType $type
     * @param array $options
     */
    public function __construct(ResolvedColumnType $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * Create a column view
     *
     * @param mixed $name
     * @return void
     */
    public function createView($name)
    {
        return $this->type->createView($name, $this->options);
    }

    /**
     * Getter for type
     *
     * @return ResolvedColumnType
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
