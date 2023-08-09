<?php

namespace SavvyWombat\Caxton;

class ContentFileFilter extends \RecursiveFilterIterator
{
    public function accept(): bool
    {
        $pathname = str_replace(Config::instance()->get('paths.base') . '/', '', $this->current()->getPathname());
        $filename = $this->current()->getFilename();

        if (
            in_array($pathname, Config::instance()->get('files.block', []))
            || (! in_array($pathname, Config::instance()->get('files.allow', []))
                && ($filename[0] === '.' || $filename[0] === '_')
            )
        ) {
            // skip hidden files and directories
            return false;
        }

        return $this->current()->isReadable();
    }
}