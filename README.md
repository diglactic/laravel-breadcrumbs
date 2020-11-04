<p align="center"><img src="logo.svg" alt="Laravel Breadcrumbs"></p>

<p align="center">
    <a href="https://github.com/diglactic/laravel-breadcrumbs/actions?query=workflow%3Atests"><img alt="Build Status" src="https://img.shields.io/github/workflow/status/diglactic/laravel-breadcrumbs/tests"></a>
    <a href="https://packagist.org/packages/diglactic/laravel-breadcrumbs"><img src="https://img.shields.io/packagist/dt/diglactic/laravel-breadcrumbs" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/diglactic/laravel-breadcrumbs"><img src="https://img.shields.io/packagist/v/diglactic/laravel-breadcrumbs" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/diglactic/laravel-breadcrumbs"><img src="https://img.shields.io/packagist/l/diglactic/laravel-breadcrumbs" alt="License"></a>
</p>

## Introduction

A simple Laravel-style way to create breadcrumbs.

This project is the [official fork](https://newsroom.diglactic.com/laravel-breadcrumbs/) of the fantastically original
[Laravel Breadcrumbs by Dave James Miller](https://github.com/davejamesmiller/laravel-breadcrumbs) and wouldn't have
been possible without
[a bunch of awesome day-one contributors](https://github.com/davejamesmiller/laravel-breadcrumbs/graphs/contributors).
Thanks, all!


Table of Contents
-----------------

- [Compatibility Chart](#compatibility-chart)
- [Getting Started](#getting-started)
- [Defining Breadcrumbs](#defining-breadcrumbs)
- [Custom Templates](#custom-templates)
- [Outputting Breadcrumbs](#outputting-breadcrumbs)
- [Structured Data](#structured-data)
- [Route-Bound Breadcrumbs](#route-bound-breadcrumbs)
- [Advanced Usage](#advanced-usage)
- [FAQ](#faq)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)


Compatibility Chart
-------------------

| Laravel | Laravel Breadcrumbs |
|---------|---------------------|
| 8.x     | 6.x                 |
| 7.x     | 6.x                 |
| 6.x     | 6.x                 |

For older Laravel versions, you'll need to use the
[original GitHub project](https://github.com/davejamesmiller/laravel-breadcrumbs).


Getting Started
---------------

### 1. Install Laravel Breadcrumbs

```bash
composer require diglactic/laravel-breadcrumbs
```

### 2. Define your breadcrumbs

Create a file called `routes/breadcrumbs.php` that looks like this:

```php
<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});
```

See the [Defining Breadcrumbs](#defining-breadcrumbs) section for more details.


### 3. Choose a template

By default, a [Bootstrap](https://getbootstrap.com/docs/4.0/components/breadcrumb/)-compatible ordered list will be
rendered, so if you're using Bootstrap 4 you can skip this step.

First, initialize the config file by running this command:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
```

Then, open `config/breadcrumbs.php` and edit this line:

```php
    'view' => 'breadcrumbs::bootstrap4',
```

The possible values are:

- `breadcrumbs::bootstrap4` – [Bootstrap 4](https://getbootstrap.com/docs/4.0/components/breadcrumb/)
- `breadcrumbs::bootstrap3` – [Bootstrap 3](https://getbootstrap.com/docs/3.4/components/#breadcrumbs)
- `breadcrumbs::bootstrap2` – [Bootstrap 2](http://getbootstrap.com/2.3.2/components.html#breadcrumbs)
- `breadcrumbs::bulma` – [Bulma](https://bulma.io/documentation/components/breadcrumb/)
- `breadcrumbs::foundation6` – [Foundation 6](https://get.foundation/sites/docs/breadcrumbs.html)
- `breadcrumbs::materialize` – [Materialize](https://materializecss.com/breadcrumbs.html)
- `breadcrumbs::uikit` – [UIkit](https://getuikit.com/docs/breadcrumb)
- `breadcrumbs::json-ld` – [JSON-LD Structured Data](https://developers.google.com/search/docs/data-types/breadcrumbs)
(&lt;script&gt; tag, no visible output)
- The path to a custom view: e.g. `partials.breadcrumbs`

See the [Custom Templates](#custom-templates) section for more details.


### 4. Output the breadcrumbs

Finally, call `Breadcrumbs::render()` in the view for each page, passing it the name of the breadcrumb to use and any
additional parameters – for example:

```blade
{{ Breadcrumbs::render('home') }}

{{ Breadcrumbs::render('category', $category) }}
```

See the [Outputting Breadcrumbs](#outputting-breadcrumbs) section for other output options, and see
[Route-Bound Breadcrumbs](#route-bound-breadcrumbs) for a way to link breadcrumb names to route names automatically.


Defining Breadcrumbs
--------------------

Breadcrumbs will usually correspond to actions or types of page. For each breadcrumb, you specify a name, the breadcrumb
title, and the URL to link it to. Since these are likely to change dynamically, you do this in a closure, and you pass
any variables you need into the closure.

The following examples should make it clear:

### Static pages

The most simple breadcrumb is probably going to be your homepage, which will look something like this:

```php
Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', route('home'));
});
```

As you can see, you simply call `$trail->push($title, $url)` inside the closure.

For generating the URL, you can use any of the standard Laravel URL-generation methods, including:

- `url('path/to/route')` (`URL::to()`)
- `secure_url('path/to/route')`
- `route('routename')` or `route('routename', 'param')` or `route('routename', ['param1', 'param2'])` (`URL::route()`)
- ``action('controller@action')`` (``URL::action()``)
- Or just pass a string URL (`'http://www.example.com/'`)

This example would be rendered like this:

```blade
{{ Breadcrumbs::render('home') }}
```

And results in this output:

> Home

### Parent links

This is another static page, but this has a parent link before it:

```php
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});
```

It works by calling the closure for the `home` breadcrumb defined above.

It would be rendered like this:

```blade
{{ Breadcrumbs::render('blog') }}
```

And results in this output:

> [Home](#) / Blog

Note that the default templates do not create a link for the last breadcrumb (the one for the current page), even when
a URL is specified. You can override this by creating your own template – see [Custom Templates](#custom-templates) for
more details.


### Dynamic titles and links

This is a dynamically generated page pulled from the database:

```php
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('blog');
    $trail->push($post->title, route('post', $post));
});
```

The `$post` object (probably an Eloquent [Model](https://laravel.com/api/8.x/Illuminate/Database/Eloquent/Model.html),
but could be anything) would simply be passed in from the view:

```blade
{{ Breadcrumbs::render('post', $post) }}
```

It results in this output:

> [Home](#) / [Blog](#) / Post Title

**Tip:** You can pass multiple parameters if necessary.


### Nested categories

Finally, if you have nested categories or other special requirements, you can call `$trail->push()` multiple times:

```php
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $trail->push($ancestor->title, route('category', $ancestor->id));
    }

    $trail->push($category->title, route('category', $category->id));
});
```

Alternatively, you could make a recursive function such as this:

```php
Breadcrumbs::for('category', function ($trail, $category) {
    if ($category->parent) {
        $trail->parent('category', $category->parent);
    } else {
        $trail->parent('blog');
    }

    $trail->push($category->title, route('category', $category->slug));
});
```

Both would be rendered like this:

```blade
{{ Breadcrumbs::render('category', $category) }}
```

And result in this:

> [Home](#) / [Blog](#) / [Grandparent Category](#) / [Parent Category](#) / Category Title


Custom Templates
----------------

### Create a view

To customize the HTML, create your own view file (e.g. `resources/views/partials/breadcrumbs.blade.php`) like this:

```blade
@unless ($breadcrumbs->isEmpty())

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if (!is_null($breadcrumb->url) && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
            @endif

        @endforeach
    </ol>

@endunless
```

(See the [resources/views/ directory](https://github.com/diglactic/laravel-breadcrumbs/tree/master/resources/views) for the built-in templates.)


#### View data

The view will receive an array called `$breadcrumbs`.

Each breadcrumb is an object with the following keys:

- `title` – The breadcrumb title
- `url` – The breadcrumb URL, or `null` if none was given
- Plus additional keys for each item in `$data` (see [Custom data](#custom-data))


### Update the config

Then update your config file (`config/breadcrumbs.php`) with the custom view name, e.g.:

```php
    'view' => 'partials.breadcrumbs', #--> resources/views/partials/breadcrumbs.blade.php
```


### Skipping the view

Alternatively you can skip the custom view and call `Breadcrumbs::generate()` to get the breadcrumbs
[Collection](https://laravel.com/api/8.x/Illuminate/Support/Collection.html) directly:

```blade
@foreach (Breadcrumbs::generate('post', $post) as $breadcrumb)
    {{-- ... --}}
@endforeach
```


Outputting Breadcrumbs
----------------------

Call `Breadcrumbs::render()` in the view for each page, passing it the name of the breadcrumb to use and any additional parameters.


### With Blade

In the page (e.g. `resources/views/home.blade.php`):

```blade
{{ Breadcrumbs::render('home') }}
```

Or with a parameter:

```blade
{{ Breadcrumbs::render('category', $category) }}
```


### With Blade layouts and @section

In the page (e.g. `resources/views/home.blade.php`):

```blade
@extends('layout.name')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection
```

Or using the shorthand syntax:

```blade
@extends('layout.name')

@section('breadcrumbs', Breadcrumbs::render('home'))
```

And in the layout (e.g. `resources/views/layout/name.blade.php`):

```blade
@yield('breadcrumbs')
```


### Pure PHP (without Blade)

In the page (e.g. `resources/views/home.php`):

```blade
<?= Breadcrumbs::render('home') ?>
```

Or use the longhand syntax if you prefer:

```blade
<?php echo Breadcrumbs::render('home') ?>
```


Structured Data
---------------

To render breadcrumbs as JSON-LD [structured data](https://developers.google.com/search/docs/data-types/breadcrumbs) (usually for SEO reasons), use `Breadcrumbs::view()` to render the `breadcrumbs::json-ld` template in addition to the normal one. For example:

```blade
<html>
    <head>
        ...
        {{ Breadcrumbs::view('breadcrumbs::json-ld', 'category', $category) }}
        ...
    </head>
    <body>
        ...
        {{ Breadcrumbs::render('category', $category) }}
        ...
    </body>
</html>
```

(Note: If you use [Laravel Page Speed](https://github.com/renatomarinho/laravel-page-speed) you may need to
[disable the `TrimUrls` middleware](https://github.com/renatomarinho/laravel-page-speed/issues/66).)

To specify an image, add it to the `$data` parameter in `push()`:

```php
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('home');
    $trail->push($post->title, route('post', $post), ['image' => asset($post->image)]);
});
```

(If you prefer to use Microdata or RDFa you will need to create a [custom template](#custom-templates).)


Route-Bound Breadcrumbs
-----------------------

In normal usage you must call `Breadcrumbs::render($name, $params...)` to render the breadcrumbs on every page. If you
prefer, you can name your breadcrumbs the same as your routes and avoid this duplication.


### Name your routes

Make sure each of your routes has a name. For example (`routes/web.php`):

```php
// Home
Route::name('home')->get('/', 'HomeController@index');

// Home > [Post]
Route::name('post')->get('/post/{id}', 'PostController@show');
```

For more details see [Named Routes](https://laravel.com/docs/8.x/routing#named-routes) in the Laravel documentation.


### Name your breadcrumbs to match

For each route, create a breadcrumb with the same name and parameters. For example (`routes/breadcrumbs.php`):

```php
// Home
Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', route('home'));
});

// Home > [Post]
Breadcrumbs::for('post', function ($trail, $id) {
    $post = Post::findOrFail($id);
    $trail->parent('home');
    $trail->push($post->title, route('post', $post));
});
```

To add breadcrumbs to a [custom 404 Not Found page](https://laravel.com/docs/8.x/errors#custom-http-error-pages), use
the name `errors.404`:

```php
// Error 404
Breadcrumbs::for('errors.404', function ($trail) {
    $trail->parent('home');
    $trail->push('Page Not Found');
});
```


### Output breadcrumbs in your layout

Call `Breadcrumbs::render()` with no parameters in your layout file (e.g. `resources/views/app.blade.php`):

```blade
{{ Breadcrumbs::render() }}
```

This will automatically output breadcrumbs corresponding to the current route. The same applies to `Breadcrumbs::generate()`:

```php
$breadcrumbs = Breadcrumbs::generate();
```

And to `Breadcrumbs::view()`:

```blade
{{ Breadcrumbs::view('breadcrumbs::json-ld') }}
```


### Route binding exceptions

It will throw an `InvalidBreadcrumbException` if the breadcrumb doesn't exist, to remind you to create one. To disable
this (e.g. if you have some pages with no breadcrumbs), first initialise the config file, if you haven't already:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
```

Then open `config/breadcrumbs.php` and set this value:

```php
    'missing-route-bound-breadcrumb-exception' => false,
```

Similarly, to prevent it throwing an `UnnamedRouteException` if the current route doesn't have a name, set this value:

```php
    'unnamed-route-exception' => false,
```


### Route model binding

Laravel Breadcrumbs uses the same model binding as the controller. For example:

```php
// routes/web.php
Route::name('post')->get('/post/{post}', 'PostController@show');
```

```php
// app/Http/Controllers/PostController.php
use App\Post;

class PostController extends Controller
{
    public function show(Post $post) // <-- Implicit model binding happens here
    {
        return view('post/show', ['post' => $post]);
    }
}
```

```php
// routes/breadcrumbs.php
Breadcrumbs::for('post', function ($trail, $post) { // <-- The same Post model is injected here
    $trail->parent('home');
    $trail->push($post->title, route('post', $post));
});
```

This makes your code less verbose and more efficient by only loading the post from the database once.

For more details see [Route Model Binding](https://laravel.com/docs/8.x/routing#route-model-binding) in the Laravel
documentation.


### Resourceful controllers

Laravel automatically creates route names for resourceful controllers, e.g. `photo.index`, which you can use when
defining your breadcrumbs. For example:

```php
// routes/web.php
Route::resource('photo', PhotoController::class);
```

```
$ php artisan route:list
+--------+----------+--------------------+---------------+-------------------------+------------+
| Domain | Method   | URI                | Name          | Action                  | Middleware |
+--------+----------+--------------------+---------------+-------------------------+------------+
|        | GET|HEAD | photo              | photo.index   | PhotoController@index   |            |
|        | GET|HEAD | photo/create       | photo.create  | PhotoController@create  |            |
|        | POST     | photo              | photo.store   | PhotoController@store   |            |
|        | GET|HEAD | photo/{photo}      | photo.show    | PhotoController@show    |            |
|        | GET|HEAD | photo/{photo}/edit | photo.edit    | PhotoController@edit    |            |
|        | PUT      | photo/{photo}      | photo.update  | PhotoController@update  |            |
|        | PATCH    | photo/{photo}      |               | PhotoController@update  |            |
|        | DELETE   | photo/{photo}      | photo.destroy | PhotoController@destroy |            |
+--------+----------+--------------------+---------------+-------------------------+------------+
```

```php
// routes/breadcrumbs.php

// Photos
Breadcrumbs::for('photo.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Photos', route('photo.index'));
});

// Photos > Upload Photo
Breadcrumbs::for('photo.create', function ($trail) {
    $trail->parent('photo.index');
    $trail->push('Upload Photo', route('photo.create'));
});

// Photos > [Photo Name]
Breadcrumbs::for('photo.show', function ($trail, $photo) {
    $trail->parent('photo.index');
    $trail->push($photo->title, route('photo.show', $photo->id));
});

// Photos > [Photo Name] > Edit Photo
Breadcrumbs::for('photo.edit', function ($trail, $photo) {
    $trail->parent('photo.show', $photo);
    $trail->push('Edit Photo', route('photo.edit', $photo->id));
});
```

For more details see [Resource Controllers](https://laravel.com/docs/8.x/controllers#resource-controllers) in the
Laravel documentation.

(Related FAQ: [Why is there no Breadcrumbs::resource() method?](#why-is-there-no-breadcrumbsresource-method).)


Advanced Usage
--------------

### Breadcrumbs with no URL

The second parameter to `push()` is optional, so if you want a breadcrumb with no URL you can do so:

```php
$trail->push('Sample');
```

The `$breadcrumb->url` value will be `null`.

The default Bootstrap templates provided render this with a CSS class of "active", the same as the last breadcrumb,
because otherwise they default to black text not grey which doesn't look right.


### Custom data

The `push()` method accepts an optional third parameter, `$data` – an array of arbitrary data to be passed to the
breadcrumb, which you can use in your custom template. For example, if you wanted each breadcrumb to have an icon, you
could do:

```php
$trail->push('Home', '/', ['icon' => 'home.png']);
```

The `$data` array's entries will be merged into the breadcrumb as properties, so you would access the icon as
`$breadcrumb->icon` in your template, like this:

```blade
<li><a href="{{ $breadcrumb->url }}">
    <img src="/images/icons/{{ $breadcrumb->icon }}">
    {{ $breadcrumb->title }}
</a></li>
```

Do not use the keys `title` or `url` as they will be overwritten.


### Before and after callbacks

You can register "before" and "after" callbacks to add breadcrumbs at the start/end of the trail. For example, to
automatically add the current page number at the end:

```php
Breadcrumbs::after(function ($trail) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $trail->push("Page $page");
    }
});
```


### Getting the current page breadcrumb

To get the last breadcrumb for the current page, use `Breadcrumb::current()`. For example, you could use this to output
the current page title:

```blade
<title>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</title>
```

To ignore a breadcrumb, add `'current' => false` to the `$data` parameter in `push()`. This can be useful to ignore
pagination breadcrumbs:

```php
Breadcrumbs::after(function ($trail) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $trail->push("Page $page", null, ['current' => false]);
    }
});
```

```blade
<title>
    {{ ($breadcrumb = Breadcrumbs::current()) ? "$breadcrumb->title –" : '' }}
    {{ ($page = (int) request('page')) > 1 ? "Page $page –" : '' }}
    Demo App
</title>
```

For more advanced filtering, use `Breadcrumbs::generate()` and Laravel's
[Collection class](https://laravel.com/api/8.x/Illuminate/Support/Collection.html) methods instead:

```php
$current = Breadcrumbs::generate()->where('current', '!==', 'false)->last();
```


### Switching views at runtime

You can use `Breadcrumbs::view()` in place of `Breadcrumbs::render()` to render a template other than the
[default one](#3-choose-a-template):

```blade
{{ Breadcrumbs::view('partials.breadcrumbs2', 'category', $category) }}
```

Or you can override the config setting to affect all future `render()` calls:

```php
Config::set('breadcrumbs.view', 'partials.breadcrumbs2');
```

```blade
{{ Breadcrumbs::render('category', $category) }}
```

Or you could call `Breadcrumbs::generate()` to get the breadcrumbs Collection and load the view manually:

```blade
@include('partials.breadcrumbs2', ['breadcrumbs' => Breadcrumbs::generate('category', $category)])
```


### Overriding the "current" route

If you call `Breadcrumbs::render()` or `Breadcrumbs::generate()` with no parameters, it will use the current route name
and parameters by default (as returned by Laravel's `Route::current()` method).

You can override this by calling `Breadcrumbs::setCurrentRoute($name, $param1, $param2...)`.


### Checking if a breadcrumb exists

To check if a breadcrumb with a given name exists, call `Breadcrumbs::exists('name')`, which returns a boolean.


### Defining breadcrumbs in a different file

If you don't want to use `routes/breadcrumbs.php`, you can change it in the config file. First initialise the config
file, if you haven't already:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
```

Then open `config/breadcrumbs.php` and edit this line:

```php
    'files' => base_path('routes/breadcrumbs.php'),
```

It can be an absolute path, as above, or an array:

```php
    'files' => [
        base_path('breadcrumbs/admin.php'),
        base_path('breadcrumbs/frontend.php'),
    ],
```

So you can use `glob()` to automatically find files using a wildcard:

```php
    'files' => glob(base_path('breadcrumbs/*.php')),
```

Or return an empty array `[]` to disable loading.


### Defining/using breadcrumbs in another package

If you are creating your own package, simply load your breadcrumbs file from your service provider's `boot()` method:

```php
use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        if (class_exists('Breadcrumbs')) {
            require __DIR__ . '/breadcrumbs.php';
        }
    }
}
```


### Dependency injection

You can use [dependency injection](https://laravel.com/docs/8.x/providers#the-boot-method) to access the `Manager`
instance if you prefer, instead of using the `Breadcrumbs::` facade:

```php
use Diglactic\Breadcrumbs\Manager;
use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot(Manager $breadcrumbs)
    {
        $breadcrumbs->register(...);
    }
}
```


### Macros

The breadcrumbs `Manager` class is [macroable](https://unnikked.ga/understanding-the-laravel-macroable-trait-dab051f09172),
so you can add your own methods. For example:

```php
Breadcrumbs::macro('pageTitle', function () {
    $title = ($breadcrumb = Breadcrumbs::current()) ? "{$breadcrumb->title} – " : '';

    if (($page = (int) request('page')) > 1) {
        $title .= "Page $page – ";
    }

    return $title . 'Demo App';
});
```

```blade
<title>{{ Breadcrumbs::pageTitle() }}</title>
```


### Advanced customizations

For more advanced customizations you can subclass `Breadcrumbs\Manager` and/or `Breadcrumbs\Generator`, then update the
config file with the new class name:

```php
    // Manager
    'manager-class' => Diglactic\Breadcrumbs\Manager::class,

    // Generator
    'generator-class' => Diglactic\Breadcrumbs\Generator::class,
```

(**Note:** Anything may change between releases. It's always a good idea to write unit tests to ensure nothing breaks
when upgrading.)


FAQ
---

### Why is there no `Breadcrumbs::resource()` method?


A few people have suggested adding `Breadcrumbs::resource()` to match
[`Route::resource()`](https://laravel.com/docs/8.x/controllers#resource-controllers), but no one has come up with a
good implementation that a) is flexible enough to deal with translations, nested resources, etc., and b) isn't overly
complex as a result.

You can always create your own using `Breadcrumbs::macro()`. Here's a good starting point:

```php
Breadcrumbs::macro('resource', function ($name, $title) {
    // Home > Blog
    Breadcrumbs::for("$name.index", function ($trail) use ($name, $title) {
        $trail->parent('home');
        $trail->push($title, route("$name.index"));
    });

    // Home > Blog > New
    Breadcrumbs::for("$name.create", function ($trail) use ($name) {
        $trail->parent("$name.index");
        $trail->push('New', route("$name.create"));
    });

    // Home > Blog > Post 123
    Breadcrumbs::for("$name.show", function ($trail, $model) use ($name) {
        $trail->parent("$name.index");
        $trail->push($model->title, route("$name.show", $model));
    });

    // Home > Blog > Post 123 > Edit
    Breadcrumbs::for("$name.edit", function ($trail, $model) use ($name) {
        $trail->parent("$name.show", $model);
        $trail->push('Edit', route("$name.edit", $model));
    });
});

Breadcrumbs::resource('blog', 'Blog');
Breadcrumbs::resource('photos', 'Photos');
Breadcrumbs::resource('users', 'Users');
```

Note that this *doesn't* deal with translations or nested resources, and it assumes that all models have a `title`
attribute (which users probably don't). Adapt it however you see fit.


Troubleshooting
---------------

#### General

- Re-read the instructions and make sure you did everything correctly.
- Start with the simple options and only use the advanced options (e.g. Route-Bound Breadcrumbs) once you understand
how it works.

#### Class 'Breadcrumbs' not found

- Try running `composer update diglactic/laravel-breadcrumbs` to upgrade.
- Try running `php artisan package:discover` to ensure the service provider is detected by Laravel.

#### Breadcrumb not found with name ...

- Make sure you register the breadcrumbs in the right place (`routes/breadcrumbs.php` by default).
    - Try putting `dd(__FILE__)` in the file to make sure it's loaded.
    - Try putting `dd($files)` in `ServiceProvider::registerBreadcrumbs()` to check the path is correct.
    - If not, try running `php artisan config:clear` (or manually delete `bootstrap/cache/config.php`) or update the
    path in `config/breadcrumbs.php`.
- Make sure the breadcrumb name is correct.
    - If using Route-Bound Breadcrumbs, make sure it matches the route name exactly.
- To suppress these errors when using Route-Bound Breadcrumbs (if you don't want breadcrumbs on some pages), either:
    - Register them with an empty closure (no push/parent calls), or
    - Set [`missing-route-bound-breadcrumb-exception` to `false`](#route-binding-exceptions) in the config file to
    disable the check (but you won't be warned if you miss any pages).

#### ServiceProvider::registerBreadcrumbs(): Failed opening required ...

- Make sure the path is correct.
- If so, check the file ownership & permissions are correct.
- If not, try running `php artisan config:clear` (or manually delete `bootstrap/cache/config.php`) or update the path
in `config/breadcrumbs.php`.

#### Undefined variable: breadcrumbs

- Make sure you use `{{ Breadcrumbs::render() }}` or `{{ Breadcrumbs::view() }}`, not `@include()`.

Contributing
------------

**Documentation:** If you think the documentation can be improved in any way, please do
[edit this file](https://github.com/diglactic/laravel-breadcrumbs/edit/master/README.md) and make a pull request.

**Bug fixes:** Please fix it and open a [pull request](https://github.com/diglactic/laravel-breadcrumbs/pulls).
([See below](#creating-a-pull-request) for more detailed instructions.) Bonus points if you add a unit test to make
sure it doesn't happen again!

**New features:** Only features with a clear use case and well-considered API will be accepted. They must be documented
and include unit tests. If in doubt, make a proof-of-concept (either code or documentation) and open a
[pull request](https://github.com/diglactic/laravel-breadcrumbs/pulls) to discuss the details. (Tip: If you want a feature
that's too specific to be included by default, see [Macros](#macros) or [Advanced customizations](#advanced-customizations)
for ways to add them.)


### Creating a pull request

The easiest way to work on Laravel Breadcrumbs is to tell Composer to install it from source (Git) using the
`--prefer-source` flag:

```bash
rm -rf vendor/diglactic/laravel-breadcrumbs
composer install --prefer-source
```

Then checkout the master branch and create your own local branch to work on:

```bash
cd vendor/diglactic/laravel-breadcrumbs
git checkout -t origin/master
git checkout -b YOUR_BRANCH
```

Now make your changes, including unit tests and documentation (if appropriate). Run the unit tests to make sure
everything is still working:

```bash
vendor/bin/phpunit -d --update-snapshots
```

Then commit the changes. [Fork the repository on GitHub](https://github.com/diglactic/laravel-breadcrumbs/fork) if you
haven't already, and push your changes to it:

```bash
git remote add YOUR_USERNAME git@github.com:YOUR_USERNAME/laravel-breadcrumbs.git
git push -u YOUR_USERNAME YOUR_BRANCH
```

Finally, browse to the repository on GitHub and create a pull request.


### Using your fork in a project

To use your own fork in a project, update the `composer.json` in your main project as follows:

```json5
{
    // ADD THIS:
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-breadcrumbs.git"
        }
    ],
    "require": {
        // UPDATE THIS:
        "diglactic/laravel-breadcrumbs": "dev-YOUR_BRANCH"
    }
}
```

Replace `YOUR_USERNAME` with your GitHub username and `YOUR_BRANCH` with the branch name (e.g. `develop`). This tells
Composer to use your repository instead of the default one.

### Unit tests

To run the unit tests:

```bash
vendor/bin/phpunit -d --update-snapshots
```

To check code coverage:

```bash
vendor/bin/phpunit --coverage-html test-coverage
```

Then open `test-coverage/index.html` to view the results. Be aware of the
[edge cases](https://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases) in
PHPUnit that can make it not-quite-accurate.


### New version of Laravel

The following files will need to be updated to run tests against a new Laravel version:

- [`composer.json`](composer.json)
    - `laravel/framework` (Laravel versions)
    - `php` (minimum PHP version)

- [`tests.yml`](.github/workflows/tests.yml)
    - `jobs.phpunit.strategy.matrix.laravel` (Laravel versions)
    - `jobs.phpunit.strategy.matrix.php` (PHP versions)
    - `jobs.phpunit.strategy.matrix.exclude` (Unsupported combinations)

If changes are required, also update:

- [`README.md`](README.md)
    - [Compatibility Chart](README.md#compatibility-chart)


License
-------

Laravel Breadcrumbs is open-sourced software licensed under the MIT license.
