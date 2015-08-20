Creating your own types
=======================

The grid system is fully customizable and allows you to add your own types or extend any of the built-in types with new properties.
You can then package these type extensions together in a grid extension.

## Creating a new type

For example, let's create a new column type that can display a DOMDocument, with the option to prezerve whitespace.
First, create a new column type that extends from the current base column type:

```php
<?php

use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class XmlType extends BaseElementType
{
    // Called once per column
    public function buildView(ElementView $view, array $options)
    {
        $view->vars['white_space'] = $options['white_space']; // Add option to view for later use
    }

    // Called for every data row
    public function bindView(ElementView $view, $item)
    {
        $view->vars['value']->preserveWhiteSpace = $view->vars['white_space'];
        $view->vars['value'] = $view->vars['value']->saveXML();
    }

    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'white_space' => true,
        ]);
    }

    public function getName()
    {
        return 'simple_xml';
    }

    public function getParent()
    {
        return 'column';
    }
}
```

Now create a grid extension and add your new column type to it:

```php
<?php

use Prezent\Grid\BaseGridExtension;

class MyGridExtension extends BaseGridExtension
{
    protected function loadElementTypes()
    {
        return [
            new XmlType(),
        ];
    }
}
```

Then add your grid extension to the `ElementTypefactory`:

```php
<?php

$elementTypeFactory = new DefaultElementTypeFactory([
    $coreExtension,
    new MyGridExtension(),
]);
```

You can now use your column type like any built-in type:

```php
<?php

$builder->addColumn('xml', 'simple_xml', [
    'label' => 'XML contents',
    'white_space' => false,
]);
```

## Extending existing types

Extending existing types is very similar to creating new types. For example, let's create an extension to add a colspan attribute.
Start off creating a column type extension that extends the column type.

```php
<?php

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColspanTypeExtension extends BaseElementTypeExtension
{
    public function configureOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'colspan' => 1,
        ]);
    }

    public function buildView(ElementView $view, array $options)
    {
        $view->vars['colspan'] = $options['colspan'];
    }

    public function getExtendedType()
    {
        return 'column';
    }
}
```

Add your new type extension to your grid extension:

```php
<?php

use Prezent\Grid\BaseGridExtension;

class MyGridExtension extends BaseGridExtension
{
    protected function loadElementTypeExtensions()
    {
        return [
            new ColspanTypeExtension(),
        ];
    }
}
```

In order to actually use this, you would need to extend the template as well. For example:

```html
{%- block grid_header_column -%}
    <th colspan="{{ colspan }}">{{ grid_header_widget(column) }}</th>
{%- endblock -%}

{%- block grid_column -%}
    <td colspan="{{ colspan }}">{{ grid_widget(column, item) }}</td>
{%- endblock -%}
```

And now you can use this new option on all column types:

```php
<?php

$builder->addColumn('name', 'string', [
    'colspan' => 2,
]);
```
