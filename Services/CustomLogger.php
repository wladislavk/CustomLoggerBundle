<?php
namespace VKR\CustomLoggerBundle\Services;

use VKR\CustomLoggerBundle\Handlers\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class CustomLogger
 * Extension to Monolog dealing with creating and using custom log files
 */
class CustomLogger
{
    /**
     * @var string $rootDir Root of the current Symfony installation + /app
     */
    protected $rootDir;

    /**
     * @param $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Sets a new Monolog object bound to a custom log file. Throws an exception if the file does not exist
     *
     * @param string $loggerName Log file name without path and extension
     * @return Logger
     * @throws FileNotFoundException
     */
    public function setLogger($loggerName)
    {
        $logger = new Logger($loggerName);
        $pathToLogfile = $this->rootDir."/logs/$loggerName.log";
        if (file_exists($pathToLogfile) !== true) {
            throw new FileNotFoundException("Log file $pathToLogfile not found on server");
        }
        $streamHandler = new StreamHandler($pathToLogfile);
        $logger->pushHandler($streamHandler);
        return $logger;
    }

    /**
     * @param string $loggerName
     * @return string
     */
    public function getLogfile($loggerName)
    {
        return $this->rootDir."/logs/$loggerName.log";
    }

}
