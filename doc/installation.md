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

use Prezent\Grid\DefaultElementTypeFactory;
use Prezent\Grid\DefaultGridFactory;
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

// Setup the type factory
$elementTypeFactory = new DefaultElementTypeFactory([
    $coreExtension,
    // Add custom extensions here
]);

// Setup the grid factory
$gridFactory = new DefaultGridFactory($elementTypeFactory, [
    // A list of your grids
    'my_grid' => new My\Grid(),
]);
```

If you have many grids you may want to consider extending the grid factory to provide lazy loading.
