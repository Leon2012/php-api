<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm\exceptions;
use leon2012\phpapi\orm\Exception as BaseException;

class ConnectionException extends BaseException
{
    public function __construct($message, $code = 512)
    {
        $format = "Connection Fail Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}