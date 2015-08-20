Action type
===========

A row action

## Options

None

## Inherited options

### `label`

The action label. Defaults to the column name.

### `url` (required)

The URL of the action. Values between braces are interpreted
as a property path and will be expanded when the column is rendered. E.g. an URL of `/path/{name}` will
be rendered as `/path/foo` if the `name` property of the row is `'foo'`.

## Parent type

[element](element.md)
