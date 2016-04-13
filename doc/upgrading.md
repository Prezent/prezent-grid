Upgrading from previous versions
================================

## Upgrading from 0.6 to 0.7

* `GridType` classes are now extendable. This requires all your grids to implement a `getName()` method.
* The `DefaultGridFactory` constructor has changed. See: [Installation](installation.md)
* You can no longer add your own `GridType` classes directly to the `DefaultGridFactory`. Put them in a `GridExtension`
  and pass your extension to the `GridTypeFactory` instead. See: [Define your grids](define-grids.md)
