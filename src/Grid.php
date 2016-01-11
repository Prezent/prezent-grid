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
     * @var ElementDescription[]
     */
    private $columns;

    /**
     * @var ElementDescription[]
     */
    private $actions;

    /**
     * Constructor
     *
     * @param ElementDescription[] $columns Column descriptions, indexed by column name
     * @param ElementDescription[] $actions Action descriptions, indexed by action name
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
     * @return ElementDescription
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
     * @return ElementDescription
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

        $view = new GridView();

        foreach ($this->columns as $name => $column) {
            $view->columns[$name] = $column->createView($name, $view);
        }

        foreach ($this->actions as $name => $action) {
            $view->actions[$name] = $action->createView($name, $view);
        }

        return $view;
    }

    /**
     * Check that all array items are element descriptions
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

            if (!($item instanceof ElementDescription)) {
                throw new UnexpectedTypeException(ElementDescription::class, $item);
            }
        }
    }
}
