<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;

/**
 * Grid
 *
 * @author Sander Marechal
 */
class Grid
{
    /**
     * @var ColumnDescription[]
     */
    private $columns;

    /**
     * Add a column
     *
     * @param string $name
     * @param ColumnDescription $column
     * @return self
     */
    public function addColumn($name, ColumnDescription $column)
    {
        $this->columns[$name] = $column;
        return $this;
    }

    /**
     * Check if a column exists
     *
     * @param string $name
     * @return bool
     */
    public function hasColumn($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * Get a column
     *
     * @param string $name
     * @return ColumnDescription
     */
    public function getColumn($name)
    {
        if (!$this->hasColumn($name)) {
            throw new InvalidArgumentException(sprintf('Column "%s" does not exist', $name));
        }

        return $this->columns[$name];
    }

    /**
     * Create the grid view
     *
     * @return GridView
     */
    public function createView()
    {
        $views = [];

        foreach ($this->columns as $name => $column) {
            $views[$name] = $column->getType()->createView($name, $column->getoptions());
        }

        return new GridView($views);
    }
}
