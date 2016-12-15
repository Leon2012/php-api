<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
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
