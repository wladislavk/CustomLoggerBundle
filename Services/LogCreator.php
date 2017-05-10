<?php
namespace VKR\CustomLoggerBundle\Services;

use Symfony\Component\Console\Output\OutputInterface;
use VKR\CustomLoggerBundle\Decorators\FileDecorator;

class LogCreator
{
    const EXTENSION = '.log';

    /** @var FileDecorator */
    private $fileDecorator;

    /** @var string */
    private $logDir;

    /** @var array */
    private $manifest;

    public function __construct(FileDecorator $fileDecorator, $logDir, array $manifest)
    {
        $this->fileDecorator = $fileDecorator;
        $this->logDir = $logDir;
        $this->manifest = $manifest;
    }

    /**
     * @param OutputInterface $output
     */
    public function createLogs(OutputInterface $output)
    {
        foreach ($this->manifest as $entry) {
            $filename = $this->logDir . '/' . $entry . self::EXTENSION;
            if (!$this->fileDecorator->fileExists($filename)) {
                $this->fileDecorator->touch($filename);
                $output->writeln('Created ' . $filename);
            }
        }
    }
}
