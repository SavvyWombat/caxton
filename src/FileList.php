<?php

namespace SavvyWombat\Caxton;

class FileList implements \Iterator
{
    protected array $files = [];
    protected int $index = 0;
    protected array $filenames = [];


    public function add(File $file): void
    {
        $this->files[$file->filename()] = $file;

        if (! in_array($file->filename(), $this->filenames)) {
            $this->filenames[] = $file->filename();
        }
    }

    public function current(): File
    {
        return $this->files[$this->filenames[$this->index]];
    }

    public function key(): int
    {
        return $this->index;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function valid(): bool
    {
        return isset($this->filenames[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }
}
