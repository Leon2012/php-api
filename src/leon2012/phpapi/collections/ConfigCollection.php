<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */
namespace leon2012\phpapi\collections;
use leon2012\phpapi\exceptions\InvalidArgumentException;

class ConfigCollection extends \leon2012\phpapi\Collection
{

    public function __construct($config = [])
    {
        foreach ($config as $name => $value) {
            $this->add($name, $value);
        }
    }

    public function valid()
    {
        if (empty($this->id)) {
            throw new InvalidArgumentException('Id is invalid');
        }

        if (empty($this->appPath)) {
            throw new InvalidArgumentException('AppPath is invalid');
        }

        if (empty($this->appNamespace)) {
            throw new InvalidArgumentException('AppNamespace is invalid');
        }

        if (empty($this->defaultRoute)) {
            throw new InvalidArgumentException('DefaultRoute is invalid');
        }
    }
}
