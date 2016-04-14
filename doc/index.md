prezent/grid
============

A framework-independent library for building and rendering generic datagrids in PHP.

## Index

1. [Installation](installation.md)
2. [Define your grids](define-grids.md)
3. [Rendering grids](rendering.md)
4. [Element type reference](types/index.md)
5. [Creating your own types](custom-types.md)
5. [Upgrading from previous versions](upgrading.md)

## Quick example

If you have any experience using Symfony Forms, then this grid library will feel very familiar. Start by defining a grid:

```php
<?php

namespace My\Grids;

use Prezent\Grid\BaseGridType;
use Prezent\Grid\GridBuilder;

class MyGridType extends BaseGridType
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
