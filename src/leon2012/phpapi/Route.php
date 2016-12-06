<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi;

class Route 
{

    public $httpMethod;
    public $regex;
    public $params;
    public $handler;
    
    public function __construct($method, $regex, $params, $handler)
    {
        $this->httpMethod = $method;
        $this->regex = $regex;
        $this->params = $params;
        $this->handler = $handler;
    }

    public function matches($str)
    {
        $regex = '/^'.$this->regex.'$/';
        return (bool)preg_match($regex, $str);
    }
}