<?php

namespace Prezent\Grid;

use Prezent\Grid\Exception\InvalidArgumentException;

/**
 * Grid extension interface
 *
 * @author Sander Marechal
 */
interface GridExtension
{
    /**
     * Check if the extension has a column type
     *
     * @param string $name
     * @return bool
     */
    public function hasColumnType($name);

    /**
     * Get a column type
     *
     * @param string $name
     * @throws InvalidArgumentException
     * @return ColumnType
     */
    public function getColumnType($name);

    /**
     * Check if the extension has a column type extension
     *
     * @param string $name
     * @return bool
     */
    public function hasColumnTypeExtensions($name);

    /**
     * Get column type extensions for a type
     *
     * @param string $name
     * @return ColumnTypeExtension[]
     */
    public function getColumnTypeExtensions($name);
}
