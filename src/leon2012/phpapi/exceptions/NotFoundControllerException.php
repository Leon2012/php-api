<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 11:36:22
 * @version $Id$
 */

namespace leon2012\phpapi\exceptions;

class NotFoundControllerException extends \leon2012\phpapi\Exception 
{
    
    public function __construct($message, $code = 504)
    {
        $format = "Not Found Controller Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}