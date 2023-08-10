<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\File;
use SavvyWombat\Caxton\FileList;
use SavvyWombat\Caxton\ViewFactory;

/**
 * Builds a sitemap file to assist search engine discovery.
 *
 * https://ahrefs.com/blog/how-to-create-a-sitemap/
 */
class GenerateSiteMap implements Middleware
{
    public function run (FileList $files, $next): FileList
    {
        $sitemap = ViewFactory::instance()->make(
            'sitemap',
            [
                'files' => $files,
            ]
        )->render();

        file_put_contents(Config::instance()->get('paths.output') . '/sitemap.xml', $sitemap);

        $files->add(new File('/sitemap.xml'));

        return $next($files);
    }
}
