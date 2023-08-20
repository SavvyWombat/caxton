<?php

namespace SavvyWombat\Caxton;

class Site
{
    protected static ?Site $instance = null;

    protected array $sourceFiles = [];
    protected array $maps = [];
    protected array $indexes = [];

    public static function instance(): Site
    {
        if (! self::$instance) {
            self::$instance = new Site();
        }

        return self::$instance;
    }

    public function addFile(File $file): void
    {
        $this->sourceFiles[] = $file;
    }

    public function sourceFiles(): array
    {
        return $this->sourceFiles;
    }

    public function addMap(array $map): void
    {
        if (isset($map['url'])) {
            $this->maps[] = $map;
        }
    }

    public function maps(): array
    {
        return $this->maps;
    }

    public function addToIndex(string $index, File $file): void
    {
        if (! isset($this->indexes[$index])) {
            $this->indexes[$index] = [];
        }

        $this->indexes[$index][] = $file;
    }

    public function index(string $index): array
    {
        if (! isset($this->indexes[$index])) {
            return [];
        }

        return $this->indexes[$index];
    }
}