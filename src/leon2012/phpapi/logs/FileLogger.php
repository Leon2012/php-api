<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\logs;

use leon2012\phpapi\exceptions\InvalidArgumentException;

class FileLogger extends AbstractLogger 
{
    private $_logFile;
    
    public function __construct($logFile)
    {
        if (!file_exists($logFile)) {
            if (!touch($logFile)) {
                throw new InvalidArgumentException('Log file ' . $logFile . ' cannot be created');
            }
        }
        if (!is_writable($logFile)) {
            throw new InvalidArgumentException('Log file ' . $logFile . ' cannot be write');
        }
        $this->_logFile = $logFile;
    }

    public function log($level, $message, array $context = array())
    {
        $log = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($level) . ': ' . $this->interpolate($message, $context) . "\n";
        file_put_contents($this->_logFile, $log, FILE_APPEND | LOCK_EX);
    }

    private function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        return strtr($message, $replace);
    }
}