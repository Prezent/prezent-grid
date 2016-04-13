<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Grid builder
 *
 * @author Sander Marechal
 */
class GridBuilder
{
    /**
     * @var ResolvedGridType
     */
    private $type;

    /**
     * @var ElementDescription[]
     */
    private $columns = [];

    /**
     * @var ElementDescription[]
     */
    private $actions = [];

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor
     *
     * @param ResolvedGridType $type
     * @param ElementTypeFactory $elementTypeFactory
     * @param array $options
     */
    public function __construct(ResolvedGridType $type, ElementTypeFactory $elementTypeFactory, array $options = [])
    {
        $this->type = $type;
        $this->elementTypeFactory = $elementTypeFactory;
        $this->options = $options;
    }

    /**
     * Add a column
     *
     * @param string $name
     * @param string|ElementType|ResolvedElementType $type
     * @param array $options
     * @return self
     */
    public function addColumn($name, $type = null, array $options = [])
    {
        if (!is_string($name)) {
            throw new UnexpectedTypeException('string', $name);
        }

        $this->columns[$name] = $this->createColumn($type, $options);

        return $this;
    }

    /**
     * Create a column
     *
     * @param string|ElementType|ResolvedElementType $type
     * @param array $options
     * @return ElementDescription
     */
    public function createColumn($type, array $options = [])
    {
        return new ElementDescription($this->resolveType($type), $options);
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
     * Add an action
     *
     * @param mixed $name
     * @param array $options
     * @return self
     */
    public function addAction($name, array $options = [])
    {
        $this->actions[$name] = $this->createColumn('action', $options);

        return $this;
    }

    /**
     * Check if an action exists
     *
     * @param string $name
     * @return bool
     */
    public function hasAction($name)
    {
        return isset($this->actions[$name]);
    }

    /**
     * Get an action
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
     * Create the grid
     *
     * @return Grid
     */
    public function getGrid()
    {
        return new Grid($this->type, $this->columns, $this->actions, $this->options);
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Resolve a column type
     *
     * @param string|ElementType|ResolvedElementType $type
     * @return ResolvedElementType
     */
    private function resolveType($type)
    {
        if (is_string($type)) {
            return $this->elementTypeFactory->getType($type);
        }

        if ($type instanceof ElementType) {
            return $this->elementTypeFactory->resolveType($type);
        }

        if ($type instanceof ResolvedElementType) {
            return $type;
        }
        
        throw new UnexpectedTypeException('string|' . ElementType::class . '|' . ResolvedElementType::class, $type);
    }
}
