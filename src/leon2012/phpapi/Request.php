<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-01 10:18:31
 * @version $Id$
 */

namespace leon2012\phpapi;

class Request  
{
    
    private $_headers = null;
    private $_cookies;
    private $_controller;
    private $_method;
    private $_params;

    public function __construct()
    {
        $this->_cookies = [];
    }

    public function getHeaders()
    {
        if ($this->_headers == null) {
            $this->_headers = new collections\HeaderCollection;
        }
    }

    
}