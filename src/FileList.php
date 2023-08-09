<?php

namespace SavvyWombat\Caxton;

class FileList
{
    protected array $files = [];

    public function add(File $file): void
    {
        $this->files[$file->filename] = $file;
    }
}
