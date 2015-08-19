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
     * @var ColumnDescription[]
     */
    private $actions;

    /**
     * Constructor
     *
     * @param ColumnDescription[] $columns Column descriptions, indexed by column name
     * @param ColumnDescription[] $actions Action descriptions, indexed by action name
     */
    public function __construct(array $columns = [], array $actions = [])
    {
        $this->checkType($columns);
        $this->checkType($actions);

        $this->columns = $columns;
        $this->actions = $actions;
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
     * Check if a action exists
     *
     * @param string $name
     * @return bool
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * Get a action
     *
     * @param string $name
     * @return ActionDescription
     */
    public function getAction($name)
    {
        if (!$this->hasAction($name)) {
            throw new InvalidArgumentException(sprintf('Action "%s" does not exist', $name));
        }

        return $this->actions[$name];
    }

    /**
     * Create the grid view
     *
     * @return GridView
     */
    public function createView()
    {
        $columnViews = [];
        $actionViews = [];

        foreach ($this->columns as $name => $column) {
            $columnViews[$name] = $column->createView($name);
        }

        foreach ($this->actions as $name => $action) {
            $actionViews[$name] = $action->createView($name);
        }

        return new GridView($columnViews, $actionViews);
    }

    /**
     * Check that all array items are column descriptions
     *
     * @param array $items
     * @return void
     */
    private function checkType(array $items)
    {
        foreach ($items as $name => $item) {
            if (!is_string($name)) {
                throw new UnexpectedTypeException('string', $name);
            }

            if (!($item instanceof ColumnDescription)) {
                throw new UnexpectedTypeException(ColumnDescription::class, $item);
            }
        }
    }
}
