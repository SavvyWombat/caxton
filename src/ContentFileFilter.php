<?php

namespace SavvyWombat\Caxton;

class ContentFileFilter extends \RecursiveFilterIterator
{
    public function accept(): bool
    {
        $allow = [];
        $block = [];
        if (! empty($_ENV['CONFIG']['files'])) {
            $allow = $_ENV['CONFIG']['files']['allow'] ?? [];
            $block = $_ENV['CONFIG']['files']['block'] ?? [];
        }

        $pathname = str_replace($_ENV['WORKING_DIR'] . '/', '', $this->current()->getPathname());
        $filename = $this->current()->getFilename();

        if (in_array($pathname, $block) || (! in_array($pathname, $allow) && ($filename[0] === '.' || $filename[0] === '_'))) {
            // skip hidden files and directories
            return false;
        }

        return $this->current()->isReadable();
    }
}