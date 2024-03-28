<?php

namespace Prezent\Grid;

/**
 * View interface
 *
 * @author Sander Marechal
 */
interface View
{
    /**
     * Bind an item to the view
     *
     * @param mixed $item
     * @return void
     */
    public function bind($item);
}
