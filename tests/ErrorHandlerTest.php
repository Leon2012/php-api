<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
use leon2012\phpapi\logs\FileLogger;
use leon2012\phpapi\ErrorHandler;
use leon2012\phpapi\exceptions\CoreException;

class ErrorHandlerTest extends PHPUnit_Framework_TestCase 
{
    
    public function testHandleException()
    {
        $logFile = '/tmp/exception.log';
        $logger = new FileLogger($logFile);

        $handler = new ErrorHandler($logger);
        $handler->registerExceptionHandler();

        throw new CoreException("testHandleException");
    }
}