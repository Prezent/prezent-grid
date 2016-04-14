<?php

namespace Prezent\Grid;

use Prezent\Grid\Extension\Core\GridType as CoreGridType;

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
    public function createBuilder($type = CoreGridType::class, array $options = []);

    /**
     * Create a grid
     *
     * @param string|GridType $type
     * @param array $options
     * @return Grid
     */
    public function createGrid($type = CoreGridType::class, array $options = []);
}
