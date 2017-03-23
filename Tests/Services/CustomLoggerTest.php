<?php
namespace VKR\CustomLoggerBundle\Tests\Services;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use VKR\CustomLoggerBundle\Services\CustomLogger;
use VKR\CustomLoggerBundle\Handlers\StreamHandler;

class CustomLoggerTest extends TestCase
{
    const ROOT_DIR = __DIR__ . '/../../../../../app';

    /**
     * @var CustomLogger
     */
    private $customLogger;

    public function setUp()
    {
        $this->customLogger = new CustomLogger(self::ROOT_DIR);
    }

    public function testSetRealLogfile()
    {
        $logger = $this->customLogger->setLogger('dev');
        $loggerHandlers = $logger->getHandlers();
        $this->assertEquals(1, sizeof($loggerHandlers));
        /** @var StreamHandler $primaryHandler */
        $primaryHandler = $loggerHandlers[0];
        $filename = $primaryHandler->getUrl();
        // for Symfony3
        if (file_exists(self::ROOT_DIR . '/../var/logs')) {
            $this->assertContains('/var/logs/dev.log', $filename);
            return;
        }
        // for Symfony2
        $this->assertContains('/app/logs/dev.log', $filename);
    }

    public function testNonExistentLogfile()
    {
        $this->expectException(FileNotFoundException::class);
        $logger = $this->customLogger->setLogger('completely_imaginary_file');
    }

    public function testCustomPath()
    {
        $logPath = __DIR__ . '/../../TestHelpers/static';
        $logger = $this->customLogger->setLogger('logfile', 'txt', $logPath);
        $loggerHandlers = $logger->getHandlers();
        $this->assertEquals(1, sizeof($loggerHandlers));
        /** @var StreamHandler $primaryHandler */
        $primaryHandler = $loggerHandlers[0];
        $filename = $primaryHandler->getUrl();
        $this->assertContains('/TestHelpers/static/logfile.txt', $filename);
    }

    public function testGetLogfile()
    {
        $logger = $this->customLogger->setLogger('dev'); // dev.log exists in Symfony by default
        $loggerFile = $this->customLogger->getLogfile('dev'); // dev.log exists in Symfony by default
        $this->assertTrue(file_exists($loggerFile));
    }
}
