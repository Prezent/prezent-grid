<?php

namespace Prezent\Grid;

/**
 * Grid factory
 *
 * @author Sander Marechal
 */
interface GridFactory
{
    /**
     * Create a grid builder
     *
     * @param string|GridType $type
     * @param array $options
     * @return GridBuilder
     */
    public function createBuilder($type = null, array $options = []);

    /**
     * Create a grid
     *
     * @param string|GridType $type
     * @param array $options
     * @return Grid
     */
    public function createGrid($type, array $options = []);
}
