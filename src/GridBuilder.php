<?php

namespace Prezent\Grid;

/**
 * Grid builder
 *
 * @author Sander Marechal
 */
class GridBuilder
{
    /**
     * @var ColumnDescription[]
     */
    private $columns = [];

    /**
     * @var ColumnTypeFactory
     */
    private $ColumnTypeFactory;

    /**
     * Constructor
     *
     * @param ColumnTypeFactory $columnTypeFactory
     */
    public function __construct(ColumnTypeFactory $columnTypeFactory)
    {
        $this->columnTypeFactory = $columnTypeFactory;
    }

    /**
     * Add a column
     *
     * @param string $name
     * @param string|ColumnType|ResolvedColumnType $type
     * @param array $options
     * @return self
     */
    public function add($name, $type = null, array $options = [])
    {
        if (!is_string($name)) {
            // TODO
            throw new \Exception();
        }

        $this->columns[$name] = $this->create($type, $options);

        return $this;
    }

    /**
     * Create a column
     *
     * @param string $name
     * @param string|ColumnType|ResolvedColumnType $type
     * @param array $options
     * @return void
     */
    public function create($type, array $options = [])
    {
        return new ColumnDescription($this->resolveType($type), $options);
    }

    /**
     * Check if a column exists
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->columns[$name]);
    }

    /**
     * Get a column
     *
     * @param string $name
     * @return ColumnDescription
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            // TODO
            throw new \Exception();
        }

        return $this->columns[$name];
    }

    /**
     * Create the grid
     *
     * @return void
     */
    public function getGrid()
    {
        $grid = new Grid();

        foreach ($this->columns as $name => $column) {
            $grid->addColumn($name, $column);
        }

        return $grid;
    }

    /**
     * Resolve a column type
     *
     * @param string|ColumnType|ResolvedColumnType $type
     * @return ResolvedColumnType
     */
    private function resolveType($type)
    {
        if ($type instanceof ColumnType) {
            $type = $this->resolveType($type);
        } elseif (is_string($type)) {
            $type = $this->columnTypeFactory->getType($type);
        } elseif (!($type instanceof ResolvedColumnType)) {
            // TODO: proper exception
            throw new \Exception('todo');
        }

        if ($parentType = $type->getParent()) {
            $parentType = $this->resolveType($parentType);
        }

        return new ResolvedColumnType($type, $parentType);
    }
}
