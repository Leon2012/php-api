<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\responses;

class JsonpResponse extends \leon2012\phpapi\Response
{

    private $_options;
    private $_callback;

    public function __construct()
    {
        parent::__construct();
        $this->_format = parent::FORMAT_JSONP;
        $this->_options = 0;
        $this->_contentType = 'application/json';

        $this->addHeader("Access-Control-Allow-Origin", "*");
        $this->addHeader("Access-Control-Allow-Credentials", true);
        $this->addHeader("Access-Control-Allow-Methods", "GET, POST, PUT");
    }

    public function setCallback($cb)
    {
        $this->_callback = $cb;
        $this->_contentType = 'text/javascript';
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
        } else {
            if (empty($this->_callback)) {
                return $ret;
            } else {
                return $this->_callback.'('.$ret.');';
            }
        }
    }

    public function setOptions($options)
    {
        $this->_options = $options;
    }
}
