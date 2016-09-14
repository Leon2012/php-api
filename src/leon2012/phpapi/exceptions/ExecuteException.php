<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-14 16:50:46
 * @version $Id$
 */
namespace leon2012\phpapi\exceptions;

class ExecuteException extends \leon2012\phpapi\Exception  
{
    
    public function __construct($message, $code = 505)
    {
        $format = "Execute Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}