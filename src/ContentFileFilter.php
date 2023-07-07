<?php

namespace SavvyWombat\Caxton;

class ContentFileFilter extends \RecursiveFilterIterator
{
    public function accept(): bool
    {
        $filename = $this->current()->getFilename();

        if ($filename[0] === '.' || $filename[0] === '_') {
            // skip hidden files and directories
            return false;
        }

        return $this->current()->isReadable();
    }
}