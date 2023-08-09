<?php

namespace SavvyWombat\Caxton;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;

class App extends Container
{
    /**
     * @return bool
     *
     * @see Application::isDownForMaintenance()
     */
    public function isDownForMaintenance(): bool
    {
        return false;
    }

    /**
     * @param string|string[] $environments
     * @return string|bool
     *
     * @see Application::environment()
     */
    public function environment(...$environments)
    {
        if(empty($environments)) {
            return 'torch';
        }

        return in_array(
            'torch',
            is_array($environments[0]) ? $environments[0] : $environments
        );
    }

    /**
     * @return string
     *
     * @see Application::getNamespace()
     */
    public function getNamespace(): string
    {
        return 'App\\';
    }
}
