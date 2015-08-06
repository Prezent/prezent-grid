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
     * @param string|Gridype $type
     * @return GridBuilder
     */
    public function createBuilder($type = null);

    /**
     * Create a grid
     *
     * @param string|Gridype $type
     * @return Grid
     */
    public function createGrid($type);
}
