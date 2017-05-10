<?php

namespace VKR\CustomLoggerBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use VKR\CustomLoggerBundle\Command\CreateLogsCommand;

class CreateLogsCommandTest extends KernelTestCase
{
    const LOG_DIR = __DIR__ . '/../../TestHelpers/static';

    /** @var CommandTester */
    private $commandTester;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new CreateLogsCommand());
        $commandName = 'vkr:logger:create';
        $command = $application->find($commandName);
        $this->commandTester = new CommandTester($command);
    }

    public function testCreateLogs()
    {
        $this->commandTester->execute([]);
        $this->assertTrue(file_exists(self::LOG_DIR . '/foo.log'));
        $this->assertTrue(file_exists(self::LOG_DIR . '/bar.log'));
    }

    public function tearDown()
    {
        if (file_exists(self::LOG_DIR . '/bar.log')) {
            unlink(self::LOG_DIR . '/bar.log');
        }
    }
}
