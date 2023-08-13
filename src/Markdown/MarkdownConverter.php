<?php

namespace SavvyWombat\Caxton\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter as BaseConverter;

class MarkdownConverter extends BaseConverter
{
    const EXTRACTION_PATTERN = "#^\s*---"
    . "[\r\n|\n]*(.*?)[\r\n|\n]+" // front end matter [1]
    . "---[\r\n|\n]*"
    . "(.*)$#s"; // content [2]

    public function __construct(array $config = [])
    {
        $environment = new Environment($config);
        $environment->addExtension(new CaxtonMarkdownExtension());

        parent::__construct($environment);
    }

    public function extractFrontMatter($content)
    {
        if (preg_match(self::EXTRACTION_PATTERN, $content, $matches)) {
            return $matches[1];
        }

        return '';
    }

    public function extractContent($content)
    {
        if (preg_match(self::EXTRACTION_PATTERN, $content, $matches)) {
            return $matches[2];
        }

        return $content;
    }
}