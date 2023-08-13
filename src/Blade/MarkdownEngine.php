<?php

namespace SavvyWombat\Caxton\Blade;

use Illuminate\Contracts\View\Engine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\ViewException;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;

class MarkdownEngine implements Engine
{
    const EXTRACTION_PATTERN = "#^\s*---"
    . "[\r\n|\n]*(.*?)[\r\n|\n]+" // front end matter [1]
    . "---[\r\n|\n]*"
    . "(.*)$#s"; // content [2]

    public function __construct(
        protected CompilerEngine $blade,
        protected CommonMarkConverter $markdown,
    ) {
        //
    }

    /**
     * @param string $path
     * @param array $data
     * @return string
     * @throws ViewException
     * @throws CommonMarkException
     */
    public function get($path, array $data = []): string
    {
        return $this->markdown->convert(
            $this->extractContent($this->blade->get($path, $data))
        )->getContent();
    }

    public function extractFrontMatter($content)
    {
        if (preg_match(self::EXTRACTION_PATTERN, $content, $matches)) {
            return $matches[1];
        }

        return $content;
    }

    public function extractContent($content)
    {
        if (preg_match(self::EXTRACTION_PATTERN, $content, $matches)) {
            return $matches[2];
        }

        return $content;
    }
}
