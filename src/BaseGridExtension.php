<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;
use Prezent\Grid\Exception\UnexpectedTypeException;

/**
 * Base grid extension
 *
 * @see GridExtension
 * @author Sander Marechal
 */
abstract class BaseGridExtension implements GridExtension
{
    /**
     * @var ColumnType[]
     */
    private $columnTypes = null;

    /**
     * @var ColumnTypeExtension[]
     */
    private $columnTypeExtensions = null;

    /**
     * {@inheritDoc}
     */
    public function hasColumnType($name)
    {
        if (null == $this->columnTypes) {
            $this->initColumnTypes();
        }

        return isset($this->columnTypes[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnType($name)
    {
        if (!$this->hasColumnType($name)) {
            throw new InvalidArgumentException(sprintf('The column type "%s" cannot be loaded', $name));
        }

        return $this->columnTypes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function hasColumnTypeExtensions($name)
    {
        if (null == $this->columnTypeExtensions) {
            $this->initColumnTypeExtensions();
        }

        return isset($this->columnTypeExtensions[$name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnTypeExtensions($name)
    {
        if (!$this->hasColumnTypeExtensions($name)) {
            return [];
        }

        return $this->columnTypeExtensions[$name];
    }

    /**
     * Get column types
     *
     * @return array
     */
    protected function loadColumnTypes()
    {
        return [];
    }

    /**
     * Get column type extensions
     *
     * @return array
     */
    protected function loadColumnTypeExtensions()
    {
        return [];
    }

    /**
     * Initialize column types
     *
     * @return void
     */
    private function initColumnTypes()
    {
        $this->columnTypes = [];

        foreach ($this->loadColumnTypes() as $columnType) {
            if (!$columnType instanceof ColumnType) {
                throw new UnexpectedTypeException(ColumnType::class, $columnType);
            }

            $this->columnTypes[$columnType->getName()] = $columnType;
        }
    }

    /**
     * Initialize column type extensions
     *
     * @return void
     */
    private function initColumnTypeExtensions()
    {
        $this->columnTypeExtensions = [];

        foreach ($this->loadColumnTypeExtensions() as $columnTypeExtension) {
            if (!$columnTypeExtension instanceof ColumnTypeExtension) {
                throw new UnexpectedTypeException(ColumnTypeExtension::class, $columnTypeExtension);
            }

            $this->columnTypeExtensions[$columnTypeExtension->getExtendedType()][] = $columnTypeExtension;
        }
    }
}
