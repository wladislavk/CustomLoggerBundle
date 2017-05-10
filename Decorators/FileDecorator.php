<?php

namespace VKR\CustomLoggerBundle\Decorators;

class FileDecorator
{
    /**
     * @param string $filename
     * @return bool
     */
    public function fileExists($filename)
    {
        return file_exists($filename);
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function touch($filename)
    {
        return touch($filename);
    }
}
