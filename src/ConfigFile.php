<?php

namespace SavvyWombat\Caxton;

class ConfigFile
{
    public static function read(string $path, ?array $empty = null): array
    {
        if ( ! file_exists($path) && ! is_null($empty)) {
            return $empty;
        }

        return json_decode(
            file_get_contents($path),
            true
        );
    }
}
