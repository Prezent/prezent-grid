prezent/grid
============

A framework-independent library for building and rendering generic datagrids in PHP.

## Installation

This extension can be installed using Composer. Tell composer to install the extension:

```bash
$ php composer.phar require prezent/grid
```

## Quick example

If you have any experience using Symfony Forms, then this grid library will feel very familiar. Start by defining a grid:

```php
<?php

namespace My\Grids;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\GridBuilder;

class MyGrid extends BaseGridType
{
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('id', 'string', [
                'label' => 'ID',
                'url'   => '/view/{id}',
            ])
            ->addColumn('name', 'string')
            ->addColumn('created', 'datetime', ['pattern' => 'yyyy qqq'])
            ->addAction('edit', ['url' => '/edit/{id}'])
        ;
    }

    public function getName()
    {
        return 'my_grid';
    }
}
```

In your controller, create the grid and assign it to your view:

```php
<?php

namespace My\Controllers;

class MyController
{
    public function indexAction()
    {
        $data = $this->db->findSomeData();
        $grid = $this->getService('grid_factory')->createGrid('my_grid');

        $this->view->data = $data;
        $this->view->grid = $grid->createView();
    }
}
```

Finally, render the grid using Twig:

```
{{ grid(grid, data) }}
```

## Documentation

The complete documentation can be found in the [doc directory](doc/index.md).
