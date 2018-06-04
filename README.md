prezent/grid
============

[![Build Status](https://travis-ci.org/Prezent/prezent-grid.svg?branch=master)](https://travis-ci.org/Prezent/prezent-grid)

A framework-independent library for building and rendering generic datagrids in PHP.

## Installation

This extension can be installed using Composer. Tell composer to install the extension:

```bash
$ php composer.phar require prezent/grid
```

Symfony users should use the [prezent/grid-bundle](https://github.com/Prezent/prezent-grid-bundle). This sets up everything
automatically and adds extra features such as router and translation integration.

## Quick example

If you have any experience using Symfony Forms, then this grid library will feel very familiar. Start by defining a grid:

```php
<?php

namespace My\Grids;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\Extension\Core\Type\DateTimeType;
use Prezent\Grid\Extension\Core\Type\StringType;
use Prezent\Grid\GridBuilder;

class MyGridType extends BaseGridType
{
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('id', StringType::class, [
                'label' => 'ID',
                'url'   => '/view/{id}',
            ])
            ->addColumn('name', StringType::class)
            ->addColumn('created', DateTimeType::class, ['pattern' => 'yyyy qqq'])
            ->addAction('edit', ['url' => '/edit/{id}'])
        ;
    }
}
```

In your controller, create the grid and assign it to your view:

```php
<?php

namespace My\Controllers;

use My\Grids\MyGridType;

class MyController
{
    public function indexAction()
    {
        $data = $this->db->findSomeData();
        $grid = $this->getService('grid_factory')->createGrid(MyGridType::class);

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
