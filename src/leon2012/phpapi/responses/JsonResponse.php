<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\responses;

class JsonResponse extends \leon2012\phpapi\Response 
{

    private $_options;
    
    public function __construct()
    {
        parent::__construct();
        $this->_format = parent::FORMAT_JSON;
        $this->_options = 0;
        $this->_contentType = 'application/json';
    }

    public function encode()
    {
        $value = [];
        $value['ret'] = $this->_ret;
        $value['msg'] = $this->_msg;
        $value['data'] = $this->_data;
        $ret =  json_encode($value, $this->_options);
        if ($ret === FALSE) {
            return null;
        }else{
            return $ret;
        }
    }

    public function setOptions($options)
    {
        $this->_options = $options;
    }
}
