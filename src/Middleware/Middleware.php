<?php

namespace SavvyWombat\Caxton\Middleware;

use SavvyWombat\Caxton\Site;

interface Middleware
{
    public function run(Site $site, callable $next): Site;
}
