<?php

namespace SavvyWombat\Caxton\Blade;

use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter as BaseConverter;

class MarkdownConverter extends BaseConverter
{
    public function __construct(array $config = [])
    {
        $environment = new Environment($config);
        $environment->addExtension(new CaxtonMarkdownExtension());

        parent::__construct($environment);
    }
}