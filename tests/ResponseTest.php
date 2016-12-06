<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

use leon2012\phpapi\responses\JsonResponse;
use leon2012\phpapi\responses\XmlResponse;

class ResponseTest extends PHPUnit_Framework_TestCase 
{

    private $output = ['ret' => 200, 'msg' => '', 'data' => 1];
    
    public function testJsonResponse()
    {
        $response = new JsonResponse();
        $response->setOutput($this->output);
        $jsonStr = $response->encode();
        $this->assertJsonStringEqualsJsonString($jsonStr, json_encode($this->output));

        $this->assertJsonStringEqualsJsonString($jsonStr, json_encode(['ret' => 201, 'msg' => '', 'data' => 1]));
    }

    public function testXmlResponse()
    {
        $response = new XmlResponse();
        $response->setOutput($this->output);
        $xmlStr = $response->encode();
        //echo $xmlStr;
        //print_r(simplexml_load_string($xmlStr, 'SimpleXMLElement' ,LIBXML_NOEMPTYTAG));
        $xml   = simplexml_load_string($xmlStr);
        $array = $this->simpleXml2array($xml);
        //print_r($array);

        $this->assertEquals($array, $this->output);
        $this->assertEquals($array, ['ret' => 201, 'msg' => '', 'data' => 1]);
    }

    public function object2array($object) 
    { 
        return @json_decode(@json_encode($object),1); 
    } 

    public function xml2array($xml)
    {
        $p = xml_parser_create();
        xml_parse_into_struct($p, $xml, $vals, $index);
        xml_parser_free($p);
        return $vals;
    }

    public function simpleXml2array(SimpleXMLElement $parent)
    {
        $array = array();
        foreach ($parent as $name => $element) {
            ($node = & $array[$name])
                && (1 === count($node) ? $node = array($node) : 1)
                && $node = & $node[];
            $node = $element->count() ? $this->simpleXml2array($element) : trim($element);
        }
        return $array;
    }
}