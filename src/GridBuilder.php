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
            throw new UnexpectedTypeException('string', $name);
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
            throw new InvalidArgumentException(sprintf('Column "%s" does not exist', $name));
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
        return new Grid($this->columns);
    }

    /**
     * Resolve a column type
     *
     * @param string|ColumnType|ResolvedColumnType $type
     * @return ResolvedColumnType
     */
    private function resolveType($type)
    {
        if (is_string($type)) {
            return $this->columnTypeFactory->getType($type);
        }

        if ($type instanceof ColumnType) {
            return $this->columnTypeFactory->resolveType($type);
        }

        if ($type instanceof ResolvedColumnType) {
            return $type;
        }
        
        throw new UnexpectedTypeException('string|' . ColumnType::class . '|' . ResolvedColumnType::class, $type);
    }
}
