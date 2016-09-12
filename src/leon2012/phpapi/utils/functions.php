<?php
/**
 * 
 * @authors Leon Peng (xingskycn@163.com)
 * @date    2016-09-12 10:58:34
 * @version $Id$
 */

namespace leon2012\phpapi\utils;

function dump($value) {
    if (is_string($value)) {
        echo $value;
    }else if (is_array($value)) {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }else{
        echo $value;
    }
}