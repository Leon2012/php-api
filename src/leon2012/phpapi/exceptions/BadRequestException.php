<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 15:49:02
 * @version $Id$
 */
namespace leon2012\phpapi\exceptions;


class BadRequestException extends \leon2012\phpapi\Exception 
{
    public function __construct($message, $code = 501)
    {
        $format = "Bad Request Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}