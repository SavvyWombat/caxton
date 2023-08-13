<?php

namespace SavvyWombat\Caxton\Blade;

use Illuminate\Contracts\View\Engine;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\ViewException;
use League\CommonMark\Exception\CommonMarkException;
use SavvyWombat\Caxton\Markdown\MarkdownConverter;

class DefaultEngine implements Engine
{
    public function __construct(
        protected CompilerEngine $blade,
        protected MarkdownConverter $markdown,
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
        return $this->markdown->extractContent($this->blade->get($path, $data));
    }
}
