<?php
/**
 * @Author: PengYe
 * @Date:   2017-05-24 16:13:09
 * @Last Modified by:   PengYe
 * @Last Modified time: 2017-05-24 17:57:07
 */

namespace leon2012\phpapi\orm;

use leon2012\phpapi\cache\CacheInterface;

class Cache 
{
	private $_store;

	/**
	 * 构造方法
	 * @param CacheInterface $store 
	 */
	public function __construct(CacheInterface $store)
	{
		$this->_store = $store;
	}

	/**
	 * 读取缓存
	 * @param  string $key 
	 * @return string 
	 */
	public function load($key)
	{
		return $this->_store->get($key);
	}

	/**
	 * 缓存数据
	 * @param  string  $key    
	 * @param  string  $data   
	 * @param  integer $expire 
	 * @return boolean         
	 */
	public function save($key, $data, $expire = 60)
	{
		return $this->_store->set($key, $data, $expire);
	}
}