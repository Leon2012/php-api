<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\traits;

trait DebugTrait
{

    public function dump($value)
    {
        if (is_string($value)) {
            echo $value;
        } elseif (is_array($value)) {
            echo "<pre>";
            print_r($value);
            echo "</pre>";
        } else {
            echo $value;
        }
    }
}
