<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 15:18:04
 * @version $Id$
 */

use leon2012\phpapi\logs\FileLogger;

class FileLoggerTest extends PHPUnit_Framework_TestCase 
{
    
    public function testLog()
    {
        $logFile = '/tmp/test.log';
        $logger = new FileLogger($logFile);
        $logger->info('info');
        $logger->emergency('emergency');
        $logger->alert('alert');
        $logger->critical('critical');
        $logger->error('error');
        $logger->warning('warning');
        $logger->notice('notice');
        $logger->debug('debug');
        $this->assertFileExists($logFile);
    }

}