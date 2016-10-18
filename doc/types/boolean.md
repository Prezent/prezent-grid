Boolean column type
===================

The boolean column type casts the value as a `bool` and displays `"yes"` or `"no"`.

## Class

`Prezent\Grid\Extension\Core\Type\BooleanType`

## Options

None

## Inherited options

### `label`

The column label in the grid header. Defaults to the column name.

### `property_path`

A path to a property in the row data. Defaults to the column name.

### `url`

If the `url` option is given, the column will be displayed as a link. Values between braces are interpreted
as a property path and will be expanded when the column is rendered. E.g. an URL of `/path/{name}` will
be rendered as `/path/foo` if the `name` property of the row is `'foo'`.

You can also supply a callback that returns an URL. The callback is passed the row as only parameter.

### `attr`

The HTML attributes for the element that is used to render it. For every attribute, values between braces
are interpreted as a property path and will be expanded when the attribute is rendered. E.g. an attribute value
of `{name}` will be rendered as `foo` if the `name` property of the row is `'foo'`.

For every attribute you can also supply a callback that returns a value. The callback is passed the row as only parameter.

## Parent type

[`Prezent\Grid\Extension\Core\Type\ColumnType`](column.md)
