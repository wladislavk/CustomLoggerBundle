<?php

namespace VKR\CustomLoggerBundle\Tests\Services;

use PHPUnit\Framework\TestCase;
use VKR\CustomLoggerBundle\Decorators\FileDecorator;
use VKR\CustomLoggerBundle\Services\LogCreator;
use VKR\CustomLoggerBundle\TestHelpers\OutputHelper;

class LogCreatorTest extends TestCase
{
    private $touchedFiles = [];

    /** @var LogCreator */
    private $logCreator;

    public function setUp()
    {
        $logDir = '/my/log/dir';
        $manifest = ['foo', 'bar'];
        $fileDecorator = $this->mockFileDecorator();
        $this->logCreator = new LogCreator($fileDecorator, $logDir, $manifest);
    }

    public function testCreateLogs()
    {
        $output = new OutputHelper();
        $this->logCreator->createLogs($output);
        $this->assertEquals(1, sizeof($output->writtenLines));
        $filename = '/my/log/dir/foo.log';
        $this->assertEquals('Created ' . $filename, $output->writtenLines[0]);
        $this->assertEquals(1, sizeof($this->touchedFiles));
        $this->assertEquals($filename, $this->touchedFiles[0]);
    }

    private function mockFileDecorator()
    {
        $fileDecorator = $this->createMock(FileDecorator::class);
        $fileDecorator->method('touch')->willReturnCallback([$this, 'touchCallback']);
        $fileDecorator->method('fileExists')->willReturnCallback([$this, 'fileExistsCallback']);
        return $fileDecorator;
    }

    public function fileExistsCallback($filename)
    {
        if (strstr($filename, 'foo')) {
            return false;
        }
        return true;
    }

    public function touchCallback($filename)
    {
        $this->touchedFiles[] = $filename;
    }
}
