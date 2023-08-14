# Caxton

A static site generator written in PHP using Blade templates and Markdown.

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

## Authoring

### Blade PHP Templates

Caxton uses Laravel's Blade template system. 
Any files in the `content` directory that end with `.blade.php` will be converted into an HTML document with the same name.

### Markdown

Caxton also allows the use of Markdown within a Blade template.
Files in the `content` directory that end with `.blade.md` will be passed through a Markdown parser before being saved as an HTML document.

Caxton supports the default CommonMark syntax using the [PHP League's](https://commonmark.thephpleague.com/) package, with one exception.
The indentation syntax to format code blocks has been disabled, meaning code blocks must be wrapped in ``` delimiters.

### Front Matter

Caxton supports front matter YAML at the start of any template file (PHP or Markdown).
The values in the front matter are injected as view data, becoming available as PHP variables within the template.

### Example

#### index.blade.php
```
---
title: Example document
---

@extends('_layouts.html')

@section('content')
  <p>Hello World</p>
@endsection
```

#### about.blade.md
```
---
title: About Markdown
---

@extends('_layouts.html')

@section('content')

This is Markdown.

@endsection
```

#### _layouts/html.blade.php
```
<html lang="en">
<head>
    <title>{{ $title }}</title>
</head>
<body>
    @yield('section')
</body>
```

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

### Output mapping

By default, the output URLs will follow the same structure as the folder paths within your public and content directories.

If you like to organise your files differently, then you can use the `output.maps` configuration to map the URLs accordingly.

For example:

```
+ content
|-+ blog
  |-+ 2018
    |-+ 10-22-it-begins
      |-  index.blade.md
      |-  pretty-picture.png
```

To output this document as `/blog/2018-10-22/it-begins`, you can use this in your `caxton.json` file:

```
{
  "output": {
    "maps": [
      {
        "path": "/blog/*/*/",
        "url": "/blog/{{ date }}/{{ slug }}/"
      }
    ]
  }
}
```

`date` and `slug` are read from the front matter of the template file.

```
---
date: 2018-10-22
slug: it-begins
---
```

Caxton will then store an internal map for all output for paths starting with `/blog/2018/10-22-it-begins/` and rewrite them as `/blog/2018-10-22/it-begins`.
This means that any resources related to the blog post (such as the `png` file) will be written to the same output URL. 

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