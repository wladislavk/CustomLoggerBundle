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
        $this->assertContains('/app/logs/dev.log', $filename);
    }

    public function testNonExistentLogfile()
    {
        $this->setExpectedException(FileNotFoundException::class);
        $logger = $this->customLogger->setLogger('completely_imaginary_file');
    }

    public function testGetLogfile()
    {
        $loggerFile = $this->customLogger->getLogfile('dev'); // dev.log exists in Symfony by default
        $this->assertTrue(file_exists($loggerFile));
    }
}
