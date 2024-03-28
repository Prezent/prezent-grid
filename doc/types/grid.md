Grid type
============

The base grid type

## Class

`Prezent\Grid\Extension\Core\Type\GridType`

## Options

### `attr`

The HTML attributes for the element that is used to render on the table.

### `row_attr`

The HTML attributes for the element that is used to render on each table row. For every attribute, values between braces
are interpreted as a property path and will be expanded when the attribute is rendered. E.g. an attribute value
of `{name}` will be rendered as `foo` if the `name` property of the row is `'foo'`.

For every attribute you can also supply a callback that returns a value. The callback is passed the row as only parameter.

## Parent type

None
