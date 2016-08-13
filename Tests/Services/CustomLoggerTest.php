<?php
namespace VKR\CustomLoggerBundle\Tests\Services;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use VKR\CustomLoggerBundle\Services\CustomLogger;
use VKR\CustomLoggerBundle\Handlers\StreamHandler;

class CustomLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $rootDir = __DIR__ . '/../../../../../app';

    /**
     * @var CustomLogger
     */
    protected $customLogger;

    public function setUp()
    {
        $this->customLogger = new CustomLogger($this->rootDir);
    }

    public function testSetRealLogfile()
    {
        $logger = $this->customLogger->setLogger('dev'); // dev.log exists in Symfony by default
        $loggerHandlers = $logger->getHandlers();
        $this->assertEquals(1, sizeof($loggerHandlers));
        /** @var StreamHandler $primaryHandler */
        $primaryHandler = $loggerHandlers[0];
        $filename = $primaryHandler->getUrl();
        // for Symfony3
        if (file_exists($this->rootDir . '/../var/logs')) {
            $this->assertContains('/var/logs/dev.log', $filename);
            return;
        }
        // for Symfony2
        $this->assertContains('/app/logs/dev.log', $filename);
    }

    public function testNonExistentLogfile()
    {
        $this->setExpectedException(FileNotFoundException::class);
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
