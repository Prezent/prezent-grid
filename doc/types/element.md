Element type
============

The base element type

## Options

### `label`

The label. Defaults to the column name.

### `url`

If the `url` option is given, the element will be displayed as a link. Values between braces are interpreted
as a property path and will be expanded when the column is rendered. E.g. an URL of `/path/{name}` will
be rendered as `/path/foo` if the `name` property of the row is `'foo'`.

You can also supply a callback that returns an URL. The callback is passed the row as only parameter.

## Parent type

None
