<?php

namespace SavvyWombat\Caxton;

use Illuminate\Support\Carbon;

class File
{
    protected \SplFileInfo $sourceFileInfo;

    public function __construct(
        protected string $filename,
        protected ?string $url = '',
        protected ?string $source = '',
    ) {
        if (empty($url)) {
            $this->url = $filename;
        }
        $this->sourceFileInfo = new \SplFileInfo($source);
        }

    public function filename(): string
    {
        return $this->filename;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function fullUrl(): string
    {
        return Config::instance()->get('base_url') . $this->url;
    }

    public function lastModified(): ?Carbon
    {
        if (empty($this->source)) {
            return null;
        }

        return Carbon::createFromTimestamp($this->sourceFileInfo->getMTime());
    }

    public function type(): string
    {
        return mime_content_type(Config::instance()->get('paths.output') . $this->filename);
    }
}
