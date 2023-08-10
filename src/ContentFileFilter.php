<?php

namespace SavvyWombat\Caxton;

class ContentFileFilter extends \RecursiveFilterIterator
{
    public function accept(): bool
    {
        $pathname = str_replace(Config::instance()->get('paths.base') . '/', '', $this->current()->getPathname());
        $filename = $this->current()->getFilename();

        if (
            in_array($pathname, Config::instance()->get('files.exclude', []))
            || (! in_array($pathname, Config::instance()->get('files.include', []))
                && ($filename[0] === '.' || $filename[0] === '_')
            )
        ) {
            // skip hidden files and directories
            return false;
        }

        return $this->current()->isReadable();
    }
}