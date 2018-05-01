<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30
 * Time: 23:51
 */

namespace AppBundle\Command;


//class KeySet for different format in keywords such as MPG, Origin
class KeySet{
    private $keySet = array();

    public function getKey($key)
    {
        return (isset($this->keySet[$key]) ? $this->keySet[$key] : null);
    }

    public function setKey($key,$value)
    {

        $this->keySet[$key] = $value;
    }
}