<?php
namespace VKR\CustomLoggerBundle\Handlers;

class StreamHandler extends \Monolog\Handler\StreamHandler
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
