# Caxton

A static site generator written in PHP using Blade templates.

## Getting started

### Installation

Caxton is available through Composer:

```
composer require savvywombat\caxton --dev
```

### Environment and configuration

Caxton looks for a `.env` file in your project's root directory. The following variables are used (with defaults defined):

- `BASE_URL` (http://localhost) - the base URL for your site
- `CONTENT_DIR` (/content) - the folder where your Blade templates are stored
- `CACHE_DIR` (/cache) - the folder under the build directory where Blade caches are stored
- `OUTPUT_DIR` (/dev) - the folder under the build directory where the final HTML is saved to

You can create different environment configurations, for example `.env.prod`.

The default environment when building is `dev`.

Caxton will also look for additional configuration in `caxton.json` and `caxton.{environment}.json`.
The various options are covered in the relevant sections below.

### Building with Caxton

To build a site, you need at least one Blade template in your content directory. Then you can run:

```
vendor/bin/caxton
```

To build for specific environment:

```
vendor/bin/caxton -e prod
```

The default environment is `dev`, so any configuration targeting the `dev` environment will be used.

## Assets (styles, scripts and images)

Common assets should be placed in your public directory. These will be copied to the build directory before your templates are built into HTML.

You can also put assets in your content directory alongside your templates to keep related assets and templates together.
 
## Directory structure

Caxton expects the following structure, but can be overridden with environment variables:

```
/project/dir/content
/project/dir/public
```

The content and public paths are configurable as ENV variables in `.env` files.

The public directory should contain common files like images and stylesheets.

The content directory is where you put your templates that Caxton will use to create your HTML.
You can include assets (images, stylesheets, scripts, and so on) alongside your templates.
These assets will then be included in the same output directory as the generated HTML.

## Building

```
vendor/bin/caxton
```

The default output directory is `/project/dir/public/dev`, but can be overridden via the environment switch:

```
vendor/bin/caxton -e prod
```

Caxton will first copy the contents of the `public` directory to the build output directory. 
It will then copy any asset files from the `content` directory, as well as build the HTML files from the templates there.

Files and directories that begin with a `.` or `_` will not be ignored.

### Include/exclude list

You can specify files for inclusion or exclusion in the `caxton.json` configuration file. File paths are relative to the working directory.

```
{
  "files": {
    "include": [
      "public/_redirects"
    ],
    "exclude": [
      "content/never-include-this-file"
    ],
  }
}
```

### Sitemap

Caxton will generate a `sitemap.xml` and add it to the root of your output directory.
Only HTML files will be included, and the last modified time will be calculated based on the source/template file.

## Publishing

Caxton simply builds a directory of content that can be published. How you publish your content is up to you.

## Why is this package called Caxton?

William Caxton is thought to be the person who introduced the printing press to England, and so ushered in a great advance in the production of books and dispersal of knowledge.

## Acknowledgements

This package uses Laravel's Blade template engine, without requiring the full Laravel framework.

Matt Stauffer has a [GitHub repository](https://github.com/mattstauffer/Torch) which has various examples of how to use parts of the framework as standalone components.
Specifically, the [view component](https://github.com/mattstauffer/Torch/tree/master/components/view) enables the use of Blade.