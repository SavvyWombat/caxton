<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\Site;

class CopyStaticFiles implements Middleware
{
    public function run(Site $site, callable $next): Site
    {
        $extensions = str_replace('.', '\.', Config::instance()->get('blade.extensions'));

        foreach ($site->sourceFiles() as $file) {
            if (! preg_match('#(.*)' . $extensions . '$#', $file->sourcePath())) {
                $outputPath = $file->outputPath();

                if (! file_exists(dirname(Config::instance()->get('paths.output') . '/' . $outputPath))) {
                    mkdir(dirname(Config::instance()->get('paths.output') . '/' . $outputPath), 0775, true);
                }
                copy($file->sourcePath(), Config::instance()->get('paths.output') . '/' . $outputPath);
            }
        }

        return $next($site);
    }
}
