Upgrading from previous versions
================================

## Upgrading from 0.8 to 0.9

HTML attributes set using the `attr` option are now parsed using the variable resolver. If you have any properties
that contain `{` or `}` braces then you should escape them using a backslash.

## Upgrading from 0.7 to 0.8

Grid and element types no longer have names. You can remove all `getName()` methods. Instead, refer to grid types and element
types using their fully qualified class names (FQCN).

### Creating a grid

Before:

```php
<?php

$grid = $gridBuilder->getGrid('my_grid');
```

After:

```php
<?php

$grid = $gridBuilder->getGrid(MyGridType::class);
```

### Defining a grid type

Before:

```php
<?php

$builder->addColumn('name', 'string', []);
```

after:

```php
<?php

use Prezent\Grid\Extension\Core\Type\StringType;

$builder->addColumn('name', StringType::class, []);
```

### Defining type extensions

Before

```php
<?php

public function getParent()
{
    return 'parent-type';
}

// or

public function getExtendedType()
{
    return 'base-type';
}
```

After:

```php
<?php

public function getParent()
{
    return ParentType::class;
}

// or

public function getExtendedType()
{
    return BaseType::class;
}
```

## Upgrading from 0.6 to 0.7

* `GridType` classes are now extendable. This requires all your grids to implement a `getName()` method. Note: If you upgrade
  to version 0.8 or above, this is not required. See the 0.7 to 0.8 upgrade notes above.
* The `DefaultGridFactory` constructor has changed. See: [Installation](installation.md)
* You can no longer add your own `GridType` classes directly to the `DefaultGridFactory`. Put them in a `GridExtension`
  and pass your extension to the `GridTypeFactory` instead. See: [Define your grids](define-grids.md)
