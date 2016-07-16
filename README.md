About
=====

This bundle is a small syntactical wrapper on top of Monolog that allows to easily switch
between several log files. It does not have any dependencies except for Monolog and Symfony
and there is nothing to do at installation.

Usage
=====

This is how the bundle is meant to be used. From your controller:

```
$logger = $this->get('vkr_custom_logger.logger');
$logger->setLogger('my_log');
$logger->addInfo('hello world');
```

The 'hello world' message with Monolog's info and timestamp will be printed to the file
at */app/logs/my_log.log*. If this file does not exist, a *FileNotFoundException* will be thrown.

If you are initializing the service manually, you will need to specify the location of the
PARENT of your /logs/ directory without a trailing slash:

```
$logger = new VKR\CustomLoggerBundle\Services\CustomLogger('/my/app/dir');
```

One more small feature is that you can get the path to the currently used log file - for some
reason original Monolog does not allow you to do it.

```
$logger->setLogger('my_log');
$loggerHandlers = $logger->getHandlers();
$primaryHandler = $loggerHandlers[0];
$filename = $primaryHandler->getUrl();
```

If the file has insufficient permissions, this bundle will not give you any exceptions,
but Monolog will - when you try to do something like ```$logger->addInfo()```.

API
===

*void CustomLogger::__construct(string $rootDir)*

Sets path to the directory one level above /logs/

*Monolog\Logger CustomLogger::setLogger(string $logName)*

The argument is a name of the log file without path and .log extension. This file must
reside at */{rootDir}/logs/*

*string CustomLogger::getLogFile(string $logName)*

Displays full path to the log file - in case you forget where it should be.

*string StreamHandler::getUrl()*

Displays full path (not URL!) to the currently active log file.
