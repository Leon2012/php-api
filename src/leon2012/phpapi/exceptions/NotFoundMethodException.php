<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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
