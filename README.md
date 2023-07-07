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

### Building with Caxton

To build a site, you need at least one Blade template in your content directory. Then you can run:

```
vendor/bin/caxton
```

To build for specific environment:

```
vendor/bin/caxton -e prod
```

## Assets (styles, scripts and images)

TBD - this package is currently under development.

## Why is this package called Caxton?

William Caxton is thought to be the person who introduced the printing press to England, and so ushered in a great advance in the production of books and dispersal of knowledge.