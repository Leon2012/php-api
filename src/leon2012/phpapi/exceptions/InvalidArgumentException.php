<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 15:05:34
 * @version $Id$
 */

namespace leon2012\phpapi\exceptions;

class InvalidArgumentException extends \leon2012\phpapi\Exception 
{
    
    public function __construct($message, $code = 502)
    {
        $format = "Invalid Argument Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}