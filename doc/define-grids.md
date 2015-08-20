Defining your grids
===================

Grids can be defined on-the-fly using a grid builder, or you can implement grids as a class and have it
managed by the grid factory. This allows you to reuse the same grids throughout your application.

In both cases, the method of building grids is the same. Grids consist of columns and actions. Columns are mapped
to your data and need to be formatted a certain way, depending on the data type. Actions are links that are added
at the end of every row so you can do something with that row. Columns are added using the `addColumn` method and
actions are added using the `addAction` method.

## Defining grids on-the-fly

Create a grid builder and add columns and actions to it.

```php
<?php

$builder = $gridFactory->createBuilder();

// Add some columns
$builder
    ->addColumn('id', 'string')
    ->addColumn('name', 'string', ['label' => 'Full name'])
;

// Add an action
$builder->addAction('delete', ['url' => '/delete/{id}']);
```

Columns consist of a name, a type and some options. Actions consist of a name and some options. Take a look at the
[reference](types/index.md) for a complete list of all types and their options.

## Mapping your row data

When you render the grid, your data must be mapped to the grid so the rows and cells will be displayed correctly.
By default, when you add a column, the grid will try to find a property in your data with that same name and use it.
Under the hood, the grid uses the [Symfony PropertyAccess Component](http://symfony.com/doc/current/components/property_access/index.html)
to retrieve values from your rows. You can set the path where the accessor looks using the `'property_path'` option.
This way it does not matter if your rows are objects, arrays or something else. An example:

```php
<?php

$data = [
    ['id' => 1, 'name' => 'John', 'partner' => ['name' => 'Jill']],
    ['id' => 2, 'name' => 'Mary', 'partner' => ['name' => 'Jack']],
];

$builder
    ->addColumn('name', 'string', [
        'property_path' => '[name]',
    ])
    ->addColumn('partner', 'string' [
        'property_path' => '[partner][name]',
    ])
;
```

## Creating grid classes

You can also define your grids as separate classes. The easiest way to do this is to extend the `BaseGridType` class. When you create
the grid using the grid factory, the `buildGrid` method will be called where you can add your columns and actions.

```php
<?php

use Prezent\Grid\BaseGridType;
use Prezent\Grid\GridBuilder;

class MyGrid extends BaseGridType
{
    public function buildGrid(GridBuilder $builder, array $options = [])
    {
        $builder
            ->addColumn('id', 'string')
            ->addColumn('name', 'string', ['label' => 'Full name'])
        ;
    }
}
```

Don't forget to add your grid to the grid factory!

```php
<?php

$gridFactory = new DefaultGridFactory($elementTypeFactory, [
    'my_grid' => new \MyGrid(),
]);
```

You can now use the grid builder to create the grid for you:

```php
<?php

$grid = $gridFactory->createGrid('my_grid');
```

You can also modify the grid afterwards if you have the grid factory create the builder instead:


```php
<?php

$builder = $gridFactory->createBuilder('my_grid');
$builder->addAction('extra', ['url' => '/extra/{id}']);

$grid = $builder->getGrid();
```
