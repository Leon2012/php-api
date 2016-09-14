<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-14 14:30:10
 * @version $Id$
 */

namespace leon2012\phpapi\responses;

use SimpleXMLElement;
use DOMDocument;

class XmlResponse extends \leon2012\phpapi\Response 
{

    private $_rootName;

    public function __construct()
    {
        parent::__construct();
        $this->_format = parent::FORMAT_XML;
        $this->_contentType = 'application/xml';
        $this->_rootName = 'root';
    }

    public function encode()
    {
        $value = [];
        $value['ret'] = $this->_ret;
        $value['msg'] = $this->_msg;
        $value['data'] = $this->_data;
        $ret =  $this->array2xml($value);
        if ($ret === FALSE) {
            return null;
        }else{
            return $ret;
        }
    }

    public function setRootName($rootName)
    {
        $this->_rootName = $rootName;
    }

    private function array2xml($arr)
    {
        $rootXml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><{$this->_rootName}></{$this->_rootName}>";
        $xml = new SimpleXMLElement($rootXml); 
        $f = create_function('$f,$c,$a',' 
            foreach($a as $k=>$v) { 
                if(is_array($v)) { 
                    $ch=$c->addChild($k); 
                    $f($f,$ch,$v); 
                } else { 
                    $c->addChild($k,$v); 
                } 
            }'); 
        $f($f,$xml,$arr); 
        //return $xml->asXML(); 
        $dom = dom_import_simplexml($xml); //simplexmlement不支持LIBXML_NOEMPTYTAG参数，所以需要用dom输出
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $dom = $doc->importNode($dom, true);
        $dom = $doc->appendChild($dom);
        return $doc->saveXML($doc, LIBXML_NOEMPTYTAG);
    }


}