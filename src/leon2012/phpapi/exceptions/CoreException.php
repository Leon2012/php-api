<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 15:46:58
 * @version $Id$
 */
namespace leon2012\phpapi\exceptions;

class CoreException extends \leon2012\phpapi\Exception 
{
    public function __construct($message, $code = 500)
    {
        $format = "Core Exception: {%s}";
        $message = sprintf($format, $message);
        parent::__construct($message, $code);
    }
}