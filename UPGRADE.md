# Upgrade Guide

## Upgrading to 7.x from 6.x

`7.x` introduces some breaking changes from `6.x`, mainly, housekeeping items.

- [Removal of `Breadcrumbs::register`](https://github.com/diglactic/laravel-breadcrumbs/commit/68cd2209ffdde5eb9f447a399287dc2196429a1f)
  , which has been deprecated since 5.0.0
- [Update Bootstrap 4 template to spec](https://github.com/diglactic/laravel-breadcrumbs/commit/4a9edc6bb3a2e1ce9fc443e170666d3724a78c4c)
- [Change default breadcrumbs template to Bootstrap 5](https://github.com/diglactic/laravel-breadcrumbs/commit/0e22a48369969980c486645a9a187d8d3838961d#diff-2dd665476127636ab1abf77af6e994805fbe299b088c22dfee9db992896c7723L28)
- [Remove legacy aliases for `DaveJamesMiller\Breadcrumbs`](https://github.com/diglactic/laravel-breadcrumbs/commit/410a67c33a2f438d42627e048d5cdf0551587cfb)

If you've already upgraded your code when switching to `5.x`, this new version should be relatively seamless.

## Upgrading to 6.x from 5.x

These upgrade notes serve to bring you off of the latest version
of [`DaveJamesMiller\Breadcrumbs`](https://github.com/davejamesmiller/laravel-breadcrumbs)
to this repository. Note that this library requires at least Laravel 6.

Begin by swapping libraries via Composer:

```shell script
composer remove davejamesmiller/laravel-breadcrumbs
composer require diglactic/laravel-breadcrumbs
```

Next, you'll need to update the following references. While we've made most classes backwards-compatible and your
project should work right away, it's a good idea to update these sooner than later as they'll be removed in a future
version.

| `davejamesmiller/laravel-breadcrumbs`                     | `diglactic/laravel-breadcrumbs`       |
| --------------------------------------------------------- | ------------------------------------- |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsManager            | Diglactic\Breadcrumbs\Manager         |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator          | Diglactic\Breadcrumbs\Generator       |
| DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider    | Diglactic\Breadcrumbs\ServiceProvider |
| DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs           | Diglactic\Breadcrumbs\Breadcrumbs     |

Once you're done, double-check your work by searching for `DaveJamesMiller\Breadcrumbs` within your application and
making any necessary replacements. Note class name changes, like `BreadcrumbsManager` to `Manager`. If you've never gone
off script, you should be all set.

If you ran into trouble following this upgrade guide, please file an issue. Happy coding!
