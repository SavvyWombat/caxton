<?php

namespace SavvyWombat\Caxton;

class Site
{
    protected static ?Site $instance = null;

    protected array $sourceFiles = [];
    protected array $maps = [];

    public static function instance(): Site
    {
        if (! self::$instance) {
            self::$instance = new Site();
        }

        return self::$instance;
    }

    public function addFile(SourceFile $file): void
    {
        $this->sourceFiles[] = $file;
    }

    public function sourceFiles()
    {
        return $this->sourceFiles;
    }

    public function addMap(array $map): void
    {
        if (isset($map['url'])) {
            $this->maps[] = $map;
        }
    }

    public function maps()
    {
        return $this->maps;
    }
}