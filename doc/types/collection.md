Collection column type
======================

The collection column type casts the values of a collection to a `string`. Object values must implement `Traversable`.
Array values are accepted as is.

## Class

`Prezent\Grid\Extension\Core\Type\CollectionType`

## Options

### `item_max_count`

If set, the maximum number of elements shown for the collection, starting at the first value. Defaults to `false` (i.e. no maximum)

### `item_property_path`

A path to a property in one of the elements of the collection. Required if the element is an object.

### `item_separator`

A string to be used when gluing the elements of the collection together. Defaults to `', '`.

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

## Parent type

[`Prezent\Grid\Extension\Core\Type\ColumnType`](column.md)
