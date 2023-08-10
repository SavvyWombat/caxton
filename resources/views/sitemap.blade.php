<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($files as $file)
@if ($file->type() === 'text/html')
  <url>
    <loc>{{ $file->fullUrl() }}</loc>
    <lastmod>{{ $file->lastModified()?->toW3cString() }}</lastmod>
  </url>
@endif
@endforeach
</urlset>
