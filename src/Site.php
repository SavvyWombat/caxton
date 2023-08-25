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

        return $this->sortIndex($index, $this->indexes->get($index));
    }

    protected function sortIndex(string $name, Collection $index)
    {
        $config = Config::instance()->get('output.index.' . $name);

        if (! $config) {
            return $index;
        }

        if (is_string($config)) {
            return $index->sortBy(function ($file) use ($config) {
                return $file->data($config);
            });
        }

        if (is_array($config)) {
            list ($field, $order) = $config;
            $sort = ($order === 'desc' ? 'sortByDesc' : 'sortBy');

            return $index->$sort(function ($file) use ($field) {
                return $file->data($field);
            });
        }
    }
}