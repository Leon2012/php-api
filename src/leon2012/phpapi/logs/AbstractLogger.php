<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\logs;
use leon2012\phpapi\LoggerInterface;

abstract class AbstractLogger implements LoggerInterface
{

    private $_outputLevel;

    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';

    private $_levelNum = [
        self::INFO      =>  1,
        self::NOTICE    =>  2,
        self::DEBUG     =>  3,
        self::WARNING   =>  4,
        self::ERROR     =>  5,
        self::CRITICAL  =>  6,
        self::ALERT     =>  7,
        self::EMERGENCY =>  8,
    ];

    public function __construct()
    {
        $this->_outputLevel = 1;
    }
    
    public function emergency($message, array $context = array())
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(self::ALERT, $message, $context);
    }
    
    public function critical($message, array $context = array())
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(self::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(self::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(self::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(self::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(self::DEBUG, $message, $context);
    }

    public function getLevelNum($level)
    {
        return isset($this->_levelNum[$level])?$this->_levelNum[$level]:0;
    }

    public function setOutputLevel($levelNum)
    {
        $this->_outputLevel = $levelNum;
    }

    public function getOutputLevel()
    {
        return $this->_outputLevel = $levelNum;
    }

    public function checkOutputLevel($level)
    {
        $levelNum = $this->getLevelNum($level);
        if ($levelNum < $this->_outputLevel) {
            return false;
        }else{
            return true;
        }
    }
}