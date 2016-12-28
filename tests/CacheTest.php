<?php
/**
 * 
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-28 10:25:48
 * @version $Id$
 */

use leon2012\phpapi\cache\CacheFactory;
use leon2012\phpapi\cache\drivers\Yac as MyYac;

class CacheTest extends PHPUnit_Framework_TestCase 
{
    
    public function testApc()
    {
        $cache = CacheFactory::getCache('apc');
        $cache->set('a', 'b');
        $this->assertEquals('b', $cache->get('a'));
    }
   
    public function testYac()
    {
        $cache = CacheFactory::getCache('yac');
        $cache->set('a', 'b');
        $cache->get('a');
        $this->assertEquals('b', $cache->get('a'));

        //$yac = new MyYac();

        // $yac = new \Yac();
 
        // for ($i = 0; $i<10; $i++) {
        //     $key =  "xxx" . rand(1, 10000);
        //     $value = str_repeat("x", rand(1, 10000));
         
        //     if (!$yac->set($key, $value)) {
        //         var_dump("write " . $i);
        //     }
         
        //     if ($value != ($new = $yac->get($key))) {
        //         var_dump("read " . $i);
        //     }
        // }
         
        // var_dump($i);
    } 

    // /**
    //  * @depends testObj
    //  */
    // public function testData($cache)
    // {
    //     echo 'driverName: '.$cache->getDriverName();
    //     $cache->set('a', 'b');
    //     $this->assertEquals('b', $cache->get('a'));
    // }

    /**
     * @dataProvider additionDrivers
     */
    public function testData1($driverName, $params)
    {
        $cache = $this->testObj($driverName, $params);
        echo 'driverName: '.$cache->getDriverName()."\n";
        $cache->set('a', 'b');
        $this->assertTrue($cache->exists('a'));
        $this->assertEquals('b', $cache->get('a'));
        $this->assertTrue($cache->delete('a'));
        $this->assertEquals('b', $cache->get('a'));
    }

    /**
     * @dataProvider additionDrivers
     */
    public function testObj($driverName, $params)
    {
        //echo 'driver:'.$driverName.' params:'.json_encode($params)."\n";
        $cache = CacheFactory::getCache($driverName, $params);
        return $cache;
    }

    public function additionDrivers()
    {
        return [
            ['yac', []],
            ['redis', ['host'=>'127.0.0.1', 'port'=>'6379', 'timeout'=>60]],
            ['memcache', ['host'=>'127.0.0.1', 'port'=>'11211', 'timeout'=>60]],
        ];
    }


}