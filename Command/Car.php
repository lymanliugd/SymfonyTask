<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30
 * Time: 23:53
 */

namespace AppBundle\Command;


class Car{
    private $features = array();

    public function getFeature($key)
    {
        return (isset($this->features[$key]) ? $this->features[$key] : null);
    }

    public function setFeature($key, $value)
    {
        $this->features[$key] = $value;
    }
}