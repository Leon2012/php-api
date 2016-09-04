<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-02 17:03:51
 * @version $Id$
 */

namespace leon2012\phpapi;

use leon2012\phpapi\CoreException;

class Config 
{
    public $id;
	public $appPath;
	public $appNamespace;

	/**
	 * init object
	 * @param array $config [description]
	 */
    public function __construct($config = [])
    {
        $this->fromArray($config);
    }

    /**
     * init data
     * @param  array  $arr [description]
     * @return [type]      [description]
     */
    public function fromArray($arr = [])
    {
    	$this->id = isset($arr['id'])?$arr['id']:'';
 		$this->appPath = isset($arr['appPath'])?$arr['appPath']:'';
 		$this->appNamespace = isset($arr['appNamespace'])?$arr['appNamespace']:'';
    }

    /**
     * valid 
     * @return [type] [description]
     */
    public function valid()
    {
    	if (empty($this->id)) {
    		throw new CoreException('Id is invalid');
    	}

    	if (empty($this->appPath))
    }
}