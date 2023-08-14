<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Blade\ViewFactory;
use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\File;
use SavvyWombat\Caxton\Site;

/**
 * Builds a sitemap file to assist search engine discovery.
 *
 * https://ahrefs.com/blog/how-to-create-a-sitemap/
 */
class GenerateSiteMap implements Middleware
{
    public function run (Site $site, $next): Site
    {
        $sitemap = ViewFactory::instance()->make(
            'sitemap',
            [
                'files' => $site->sourceFiles(),
            ]
        )->render();

        file_put_contents(Config::instance()->get('paths.output') . '/sitemap.xml', $sitemap);

        $site->addFile(new File('/sitemap.xml', '/sitemap.xml'));

        return $next($site);
    }
}
