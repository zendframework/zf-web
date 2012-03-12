<?php
class EntryFilter extends FilterIterator
{
    public function accept()
    {
        $file = $this->getInnerIterator()->current();

        if (!$file instanceof SplFileInfo) {
            return false;
        }

        if ($file->isDir() || $file->isDot()) {
            return false;
        }

        if ($file->getBasename() == $file->getBasename('.php')) {
            return false;
        }

        return true;
    }
}
