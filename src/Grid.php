<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

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
     * Constructor
     *
     * @param ColumnDescription[] $columns Column descriptions, indexed by column name
     */
    public function __construct(array $columns = [])
    {
        foreach ($columns as $name => $column) {
            if (!is_string($name)) {
                throw new UnexpectedTypeException('string', $name);
            }

            if (!($column instanceof ColumnDescription)) {
                throw new UnexpectedTypeException(ColumnDescription::class, $column);
            }
        }

        $this->columns = $columns;
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
