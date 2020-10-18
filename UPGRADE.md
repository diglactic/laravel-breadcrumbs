# Upgrade Guide

## Upgrading To 6.0 From 5.x

These upgrade notes serve to bring you off of the latest version of [`DaveJamesMiller\Breadcrumbs`](https://github.com/davejamesmiller/laravel-breadcrumbs)
to this repository. Note that this library requires at least Laravel 6.

Begin by swapping libraries via Composer:

```shell script
composer remove davejamesmiller/laravel-breadcrumbs
composer require diglactic/laravel-breadcrumbs
```

Next, you'll need to update the following references. While we've made most classes backwards-compatible and your project
should work right away, it's a good idea to update these sooner than later as they'll be removed in a future version.

| `davejamesmiller/laravel-breadcrumbs`                     | `diglactic/laravel-breadcrumbs`       |
| --------------------------------------------------------- | ------------------------------------- |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsManager            | Diglactic\Breadcrumbs\Manager         |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator          | Diglactic\Breadcrumbs\Generator       |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider    | Diglactic\Breadcrumbs\ServiceProvider |
| DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs           | Diglactic\Breadcrumbs\Breadcrumbs     |

Once you're done, double-check your work by searching for `DaveJamesMiller\Breadcrumbs` within your application and
making any necessary replacements. If you've never gone off script, you should be all set.

If you ran into trouble following this upgrade guide, please file an issue. Happy coding!
