<?php

/* 
 * redis extends
 */

namespace mplo\Lib;

use Illuminate\Support\Facades\Redis as Predis;

class Redis extends Predis{
    
    public static function hset($key, $childKey, $data){
        return parent::hset($key, $childKey, self::dataEncode($data));
    }
    
    public static function hget($key, $childKey){
        return parent::dataDecode(Predis::hget($key, $childKey));
    }
    
    public static function dataEncode($data){
        return json_encode($data);
    }
    
    public static function dataDecode($data){
        return $data ? json_decode($data) : $data;
    }
}