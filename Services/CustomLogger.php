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
    const DEFAULT_FILE_EXTENSION = 'log';

    /**
     * @var string Root of the current Symfony installation + /app
     */
    protected $rootDir;

    /**
     * @var string Absolute path to directory where logs are written
     */
    protected $logDir;

    /**
     * @var string Name of log file with extension but without path
     */
    protected $logfileName;

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
     * @param string $extension
     * @param string|null $logDir
     * @return Logger
     * @throws FileNotFoundException
     */
    public function setLogger($loggerName, $extension = self::DEFAULT_FILE_EXTENSION, $logDir = null)
    {
        $this->setLogDir($logDir);
        $this->logfileName = $loggerName . '.' . $extension;
        $logger = new Logger($loggerName);
        $pathToLogfile = $this->logDir . "/" . $this->logfileName;
        if (file_exists($pathToLogfile) !== true) {
            throw new FileNotFoundException("Log file $pathToLogfile not found on server");
        }
        $streamHandler = new StreamHandler($pathToLogfile);
        $logger->pushHandler($streamHandler);
        return $logger;
    }

    /**
     * @param string|null $filename
     * @return string
     */
    public function getLogfile($filename = null)
    {
        if ($this->logDir === null) {
            throw new \RuntimeException('Call $this->setLogger() first');
        }
        // for backwards compatibility with v1.0
        if ($filename) {
            return $this->logDir . "/" . $filename . "." . self::DEFAULT_FILE_EXTENSION;
        }
        if ($this->logfileName === null) {
            throw new \RuntimeException('Call $this->setLogger() first');
        }
        return $this->logDir . "/" . $this->logfileName;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->logDir;
    }

    /**
     * @param string|null $logDir
     * @throws FileNotFoundException
     */
    protected function setLogDir($logDir)
    {
        if ($logDir !== null) {
            if (file_exists($logDir) && is_dir($logDir)) {
                $this->logDir = $logDir;
                return;
            }
            throw new FileNotFoundException("$logDir does not exist or is not a directory");
        }
        $symfony3Dir = $this->rootDir . '/../var/logs';
        if (file_exists($symfony3Dir) && is_dir($symfony3Dir)) {
            $this->logDir = $symfony3Dir;
            return;
        }
        $symfony2Dir = $this->rootDir . '/logs';
        if (file_exists($symfony2Dir) && is_dir($symfony2Dir)) {
            $this->logDir = $symfony2Dir;
            return;
        }
        throw new FileNotFoundException("Either $symfony2Dir or $symfony3Dir must exist or $logDir argument must be set");
    }

}
