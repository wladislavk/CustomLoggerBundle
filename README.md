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
at ```/app/logs/my_log.log``` (```/var/logs/my_log.log``` for Symfony3). If this file does not exist,
a ```FileNotFoundException``` will be thrown.

If you are initializing the service manually, you will need to specify the location of your
```app/``` directory without a trailing slash:

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

Sets path to the ```app/``` directory.

*Monolog\Logger CustomLogger::setLogger(string $logName, string $extension = '.log', string $logDir = null)*

The first argument is a name of the log file without path and extension. If the third argument
is not given, the directory will default to ```/app/logs``` for Symfony2, and ```/var/logs```
for Symfony3.

*string CustomLogger::getLogfile(string $logName = null)*

Displays full path to the log file - in case you forget where it should be. Providing the
optional argument is deprecated.

*string StreamHandler::getLogDir()*

Displays full path to the directory with log files. Should be called only after ```setLogger()```.
