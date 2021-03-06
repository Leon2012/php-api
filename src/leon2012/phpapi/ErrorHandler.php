<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\logs\AbstractLogger;

class ErrorHandler
{

    private $_logger;

    /**
     * ErrorHandler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    /**
     *
     */
    public function registerExceptionHandler()
    {
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @param int $errorTypes
     */
    public function registerErrorHandler($errorTypes = -1)
    {
        set_error_handler([$this, 'handleError'], $errorTypes);
    }

    /**
     * @param $e
     */
    public function handleException($e)
    {
        $level = AbstractLogger::ERROR;
        $this->_logger->log(
            $level,
            sprintf('Uncaught Exception %s: "%s" at %s line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()),
            ['exception' => $e]
        );
    }

    /**
     * @param $code
     * @param $message
     * @param string $file
     * @param int    $line
     * @param array  $context
     */
    public function handleError($code, $message, $file = '', $line = 0, $context = [])
    {
        $level = AbstractLogger::CRITICAL;
        //$msg = sprintf('Uncaught Error %s: "%s" at %s line %s', self::codeToString($code), $message, $file, $line);
        $this->_logger->log(
            $level,
            sprintf('Uncaught Error %s: "%s" at %s line %s', self::codeToString($code), $message, $file, $line),
            []
        );
        //$this->_logger->log($level, self::codeToString($code).': '.$message, ['code' => $code, 'message' => $message, 'file' => $file, 'line' => $line]);
    }

    /**
     * @param $code
     * @return string
     */
    private static function codeToString($code)
    {
        switch ($code) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
        }

        return 'Unknown PHP error';
    }
}
