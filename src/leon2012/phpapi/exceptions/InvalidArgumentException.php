<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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