<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\orm\exceptions;
use leon2012\phpapi\orm\Exception as BaseException;

class InvalidConfigException extends BaseException
{
    public function __construct($message, $code = 511)
    {
        $format = "Invalid Database Config Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}
