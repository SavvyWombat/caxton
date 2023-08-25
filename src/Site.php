<?php

namespace SavvyWombat\Caxton;

use Illuminate\Support\Collection;

class Site
{
    protected static ?Site $instance = null;

    protected Collection $sourceFiles;
    protected Collection $maps;
    protected Collection $indexes;

    protected function __construct()
    {
        $this->sourceFiles = new Collection();
        $this->maps = new Collection();
        $this->indexes = new Collection();
    }

    public static function instance(): Site
    {
        if (! self::$instance) {
            self::$instance = new Site();
        }

        return self::$instance;
    }

    public function addFile(File $file): void
    {
        $this->sourceFiles->push($file);
    }

    public function sourceFiles(): Collection
    {
        return $this->sourceFiles;
    }

    public function addMap(array $map): void
    {
        if (isset($map['url'])) {
            $this->maps->push($map);
        }
    }

    public function maps(): Collection
    {
        return $this->maps;
    }

    public function addToIndex(string $index, File $file): void
    {
        if (! $this->indexes->has($index)) {
            $this->indexes->put($index, new Collection());
        }

        $this->indexes->get($index)->push($file);
    }

    public function index(string $index): Collection
    {
        if (! $this->indexes->has($index)) {
            return new Collection();
        }

        return $this->indexes->get($index);
    }
}