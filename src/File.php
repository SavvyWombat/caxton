<?php

namespace SavvyWombat\Caxton;

use Illuminate\Support\Carbon;

class File
{
    public function __construct(
        protected string $sourcePath,
        protected string $outputPath,
        protected ?array $data = [],
    ) {
        $this->extensions = str_replace('.', '\.', Config::instance()->get('blade.extensions'));
    }

    public function sourcePath(): string
    {
        return $this->sourcePath;
    }

    public function outputPath(): string
    {
        foreach (Site::instance()->maps() as $map) {
            if (str_starts_with($this->outputPath, $map['path'])) {
                return str_replace($map['path'], $map['url'], $this->outputPath);
            }
        }

        return $this->outputPath;
    }

    public function url(): string
    {
        foreach (Site::instance()->maps() as $map) {
            if (str_starts_with($this->outputPath, $map['path'])) {
                return str_replace($map['path'], $map['url'], $this->outputPath);
            }
        }

        return $this->outputPath;
    }

    public function fullUrl(): string
    {
        return Config::instance()->get('base_url') . '/' . $this->url();
    }

    public function data(): array
    {
        return $this->data;
    }

    public function type(): string
    {
        return mime_content_type(Config::instance()->get('paths.output') . '/' . $this->outputPath());
    }

    public function lastModified(): ?Carbon
    {
        return Carbon::createFromTimestamp(filemtime(Config::instance()->get('paths.output') . '/' . $this->outputPath()));
    }
}