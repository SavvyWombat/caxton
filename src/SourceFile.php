<?php

namespace SavvyWombat\Caxton;

class SourceFile
{
    public function __construct(
        protected string $sourcePath,
        protected string $outputPath,
        protected ?array $data = [],
    ) {
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

    public function data(): array
    {
        return $this->data;
    }
}