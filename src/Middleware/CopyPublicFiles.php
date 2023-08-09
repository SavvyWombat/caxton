<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\ContentFileFilter;
use SavvyWombat\Caxton\File;
use SavvyWombat\Caxton\FileList;

class CopyPublicFiles implements Middleware
{
    public function run(FileList $files, callable $next): FileList
    {
        $publicFiles = new \RecursiveIteratorIterator(
            new ContentFileFilter(
                new \RecursiveDirectoryIterator(
                    Config::instance()->get('paths.public'),
                    \FilesystemIterator::SKIP_DOTS
                )
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($publicFiles as $publicFile) {
            $subpath = str_replace(
                Config::instance()->get('paths.public'),
                '',
                $publicFile->getRealPath()
            );

            if (!file_exists(Config::instance()->get('paths.output') . dirname($subpath))) {
                // directories need the 'execute/search' bit
                // permissions are subject to the umask value in the running environment
                mkdir(Config::instance()->get('paths.output') . dirname($subpath), 0775, true);
            }

            if (!is_dir($publicFile->getRealPath())) {
                copy($publicFile->getRealPath(), Config::instance()->get('paths.output') . $subpath);

                $files->add(new File($subpath));
            }
        }

        return $next($files);
    }
}
