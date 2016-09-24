# Laravel Sketchpad

## What it is

Sketchpad is an interactive front-end for your Laravel back-end; a place to write, test, experiment and execute code, or just a place to group useful tools and functions you want easy access to.

## What it does

It lists controllers and methods from folders of your choice, and allows you to run them without setting up routing, views, etc.

You can navigate to, and run any controller method, even modify parameter values, all from a friendly UI.

It comes with a whole bunch of useful tools and functions to make it easy to rattle out quick coding tests.

Install the gulp plugin to see updates to your code, live!

## Installation

To install the package, run the following from your site's root directory:

```
composer require davestewart/laravel-sketchpad
```

Once installed, you'll need to add a reference to the Sketchpad service provider in your app config, found at `config/app.php`.

At the bottom of the entry to `providers` add the following entry:

```
davestewart\sketchpad\SketchpadServiceProvider::class
```

Now, load your site and visit the top-level URL `sketchpad/`, i.e.:

```
http://yoursite.dev/sketchpad/
```

The Sketchpad setup page should load, and you will be able to configure paths, after which the application should install.

If you get any errors, the installer will attempt to help you through them.

## More info

For usage instructions, see the [GitHub wiki](https://github.com/davestewart/laravel-sketchpad/wiki).
