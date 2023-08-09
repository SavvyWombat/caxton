<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\FileList;

interface Middleware
{
    public function run(FileList $files, callable $next): FileList;
}
