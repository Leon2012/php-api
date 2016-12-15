<?php
/**
 *
 * @authors LeonPeng (leon.peng@live.com)
 * @date    2016-12-05 17:16:10
 * @version $Id$
 */

namespace leon2012\phpapi\validations;

class StringValidator extends leon2012\phpapi\Validator
{

    public $min;
    public $max;
    public $length;

    public function __construct($length=[], $min = 0, $max = 255, $message = '')
    {
        if (!empty($length) && count($length) == 2) {
            $this->min = $length[0];
            $this->max = $length[1];
            $this->length = $length;
        }
        if (empty($message)) {
            $this->message = '';
        } else {
            $sthis->message = $message;
        }
    }

    public function valid()
    {

    }
}
