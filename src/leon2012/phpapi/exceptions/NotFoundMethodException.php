<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 11:35:20
 * @version $Id$
 */
namespace leon2012\phpapi\exceptions;

class NotFoundMethodException extends \leon2012\phpapi\Exception 
{
    
    public function __construct($message, $code = 503)
    {
        $format = "Not Found Method Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}