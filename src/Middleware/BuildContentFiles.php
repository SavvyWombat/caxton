<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Config;
use SavvyWombat\Caxton\ContentFileFilter;
use SavvyWombat\Caxton\File;
use SavvyWombat\Caxton\FileList;
use SavvyWombat\Caxton\ViewFactory;

class BuildContentFiles implements Middleware
{
    public function run(FileList $files, callable $next): FileList
    {
        $contentFiles = new \RecursiveIteratorIterator(
            new ContentFileFilter(
                new \RecursiveDirectoryIterator(
                    Config::instance()->get('paths.content'),
                    \FilesystemIterator::SKIP_DOTS
                )
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($contentFiles as $contentFile) {
            $subpath = str_replace(
                Config::instance()->get('paths.content'),
                '',
                $contentFile->getRealPath()
            );

            if (!file_exists(Config::instance()->get('paths.output') . dirname($subpath))) {
                // directories need the 'execute/search' bit
                // permissions are subject to the umask value in the running environment
                mkdir(Config::instance()->get('paths.output') . dirname($subpath), 0775, true);
            }

            if (str_ends_with($subpath, '.blade.php')) {
                $subpath = str_replace('.blade.php', '', $subpath);
                $output = ViewFactory::instance()->make(
                    str_replace('/', '.', $subpath),
                    [
                        'page' => null,
                        'url' => Config::instance()->get('base_url') . (str_ends_with($subpath, 'index') ? substr($subpath, 0, -5) : $subpath),
                    ]
                )->render();

                file_put_contents(Config::instance()->get('paths.output') . $subpath . '.html', $output);
                $files->add(new File($subpath . '.html'));
            } else if (!is_dir($contentFile->getRealPath())) {
                copy($contentFile->getRealPath(), Config::instance()->get('paths.output') . $subpath);
                $files->add(new File($subpath));
            }
        }

        return $next($files);
    }
}