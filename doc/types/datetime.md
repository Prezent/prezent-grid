Datetime column type
====================

The datetime column formats a `DateTime` or `DateTimeImmutable` object as a string.

## Class

`Prezent\Grid\Extension\Core\Type\DateTimeType`

## Options

### `date_format`

The date format to use. Should be one of the PHP `IntlDateFormatter` constants. Defaults to `\IntlDateFormatter::MEDIUM`.

### `locale`

The locale to be used by the date formatter. Defaults to the PHP default.

### `pattern`

The pattern to use for rendering. This overrides the `date_format` and `time_format` options. Should be a string
in [ICU pattern format](http://userguide.icu-project.org/formatparse/datetime).

### `time_format`

The time format to use. Should be one of the PHP `IntlDateFormatter` constants. Defaults to `\IntlDateFormatter::MEDIUM`.

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
