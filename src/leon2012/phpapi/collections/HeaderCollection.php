<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 13:57:52
 * @version $Id$
 */

namespace leon2012\phpapi\collections;

class HeaderCollection extends \leon2012\phpapi\Collection  
{
    
    public function __construct ()
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }else if (function_exists('http_get_request_headers')) {
            $headers = http_get_request_headers();
        }else{
            foreach ($_SERVER as $name => $value) {
                if (strncmp($name, 'HTTP_', 5) === 0) {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$name] = $value;
                }
            }
        }
        foreach ($headers as $name => $value) {
            $this->add($name, $value);
        }
    }

}