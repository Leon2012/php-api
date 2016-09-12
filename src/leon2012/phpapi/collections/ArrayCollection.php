<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 15:09:53
 * @version $Id$
 */
namespace leon2012\phpapi\collections;


class ArrayCollection extends \leon2012\phpapi\Collection   
{
    
    public function __construct($array = [])
    {
        foreach ($array as $name => $value) {
            $this->add($name, $value);
        }
    }
}