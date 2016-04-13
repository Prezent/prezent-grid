Installation
============

This extension can be installed using Composer. Tell composer to install the extension:

```bash
$ php composer.phar require prezent/grid
```

## Setup

Symfony users should use the [prezent/grid-bundle](https://github.com/Prezent/prezent-grid-bundle). This sets up everything
automatically and adds extra features such as router and translation integration.

Create the grid factory and add your grids:

```php
<?php

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\DefaultGridFactory;
use Prezent\Grid\DefaultGridTypeFactory;
use Prezent\Grid\Extension\Core\CoreExtension;
use Prezent\Grid\VariableResolver\CallbackResolver;
use Prezent\Grid\VariableResolver\ChainResolver;
use Prezent\Grid\VariableResolver\PropertyPathResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

// Setup the core extension, which provides the basic column types and actions
$accessor = PropertyAccess::createPropertyAccessor();
$coreExtension = new CoreExtension($accessor, new ChainResolver([
    new CallbackResolver(),
    new PropertyPathResolver($accessor)
]));

// Setup the grid type factory which has all your grids
$gridTypeFactory = new DefaultGridTypeFactory([
    $coreExtension,
    // Add custom extensions here
]);

// Setup the element type factory which has all your column and action types
$elementTypeFactory = new DefaultElementTypeFactory([
    $coreExtension,
    // Add custom extensions here
]);

// Setup the main grid factory
$gridFactory = new DefaultGridFactory($gridTypeFactory, $elementTypeFactory);
```

To use named grid classes and custom column and action types, create your own grid extension and add your extension to the
`GridTypeFactory` and `ElementTypeFactory`:

```php
<?php

use Prezent\Grid\BaseGridExtension;
use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\DefaultGridTypeFactory;

class MyGridExtension extends BaseGridExtension
{
    protected function loadGridTypes()
    {
        return [
            new MyGridType(),
        ];
    }

    protected function loadElementTypes()
    {
        return [
            new MyCustomColumnType(),
        ];
    }
}
```

If you have many grids you may want to consider implementing your own `GridTypeFactory` to provide lazy loading.

## Twig extension

This library provides a Twig extension to render grids. You can set up the extension like so:

```php
<?php

use Prezent\Grid\Twig\TwigExtension;
use Prezent\Grid\Twig\TwigRenderer;

$extension = new GridExtension(new GridRenderer());
```
