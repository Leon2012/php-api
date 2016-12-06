<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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