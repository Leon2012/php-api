<?php

require_once __DIR__."/../vendor/autoload.php";

use leon2012\phpapi\logs\FileLogger;
use leon2012\phpapi\ErrorHandler;
use leon2012\phpapi\exceptions\CoreException;

$logFile = '/tmp/error.log';
$logger = new FileLogger($logFile);

$handler = new ErrorHandler($logger);
// $handler->registerExceptionHandler();
// throw new CoreException("testHandleException");



$handler->registerErrorHandler();
trigger_error('testHandleError', E_USER_NOTICE);