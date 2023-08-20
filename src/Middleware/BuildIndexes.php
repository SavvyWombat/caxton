<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Site;

class BuildIndexes implements Middleware
{
    public function run(Site $site, callable $next): Site
    {
        foreach ($site->sourceFiles() as $sourceFile) {
            if (isset($sourceFile->data()['index'])) {
                var_dump($sourceFile->data()['index']);
                $site->addToIndex($sourceFile->data()['index'], $sourceFile);
            }
        }

        return $next($site);
    }
}
