Rendering grids
===============

This grid library is integrated with Twig to provide rendering functions. You don't need to use Twig, but it makes rendering
a lot easier. The easiest way to render your grid is to simply call the `grid()` twig function:

```html
{{ grid(grid, data) }}
```

You can also render each part of the grid manually in order to customize it:

```html
<table>
    <thead>
        {{ grid_header_row(grid) }}
    </thead>
    <tbody>
        {{ for row in data }}
            <tr>
                {{ grid_row(grid, row) }}
            </tr>
        {{ endfor }}
    </tbody>
</table>
```

Or even:

```html
<table>
    <thead>
        <th>{{ grid_header_widget(grid.columns.id) }}</th>
        <th>{{ grid_header_widget(grid.columns.name) }}</th>
        <th>Actions</th>
    </thead>
    <tbody>
        {{ for row in data }}
            <tr>
                {{ grid_column(grid.columns.id, row) }}
                {{ grid_column(grid.columns.name, row) }}
                <td>
                    {{ grid_action(grid.actions.edit, row) }}
                    {{ grid_action(grid.actions.delete, row) }}
                </td>
            </tr>
        {{ endfor }}
    </tbody>
</table>
```

## Twig functions

The following twig functions are available to render your grids:

### grid(grid, data[, vars])

Render an entire grid. The variables are exposed to the inner elements of the grid.

### grid\_header\_row(grid[, vars])

Render a table row of column headers

### grid\_header\_column(grid-column[, vars])

Render a column headers

### grid\_header\_widget(grid-column[, vars])

Render a column header content, such as the label

### grid\_row(grid, data[, vars])

Render a table data row

### grid\_column(grid-column, data[, vars])

Render a table data column

### grid\_widget(grid-column, data[, vars])

Render a table data column content

### grid\_actions(grid, data[, vars])

Render an actions column

### grid\_action(grid-action, data[, vars])

Render an individual action

## Template customisation

You can customize the rendering of every aspect of the grid by extending the `grid.html.twig` template. To use your own template,
pass it to the grid renderer:

```php
<?php

$renderer = new TwigRenderer('/path/to/your/template.html.twig');
```

Aside from the usual Twig template inheritance, you can also add specific blocks for every widget based on the type inheritance.
For example, the `string` column type extends the `column` type, which extends the `element` type. When the column header is rendered
then the renderer will search for the following blocks (in order) and use that to render the header:

* `grid_header_string_widget`
* `grid_header_column_widget`
* `grid_header_element_widget`
* `grid_header_widget`

The same applies to the widgets used for the cell rows:

* `grid_string_widget`
* `grid_column_widget`
* `grid_element_widget`
* `grid_widget`

This also works if you add your own column types, as long as they extend the existing `element` type or one of it's children.
