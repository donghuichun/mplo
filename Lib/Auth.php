<?php

/* 
 * 账号
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Lib;

class Auth{
    
    /**
     * 账号id
     * @var type 
     */
    public static $userId;
    
    /**
     * 租户id
     * @var type 
     */
    public static $tId;
    
    /**
     * 访问中租户id
     * @var type 
     */
    public static $tIdIn;
    
    public static function setUserId($userId){
        self::$userId = $userId;
    }
    
    public static function getUserId(){
        return self::$userId;
    }
    
    public static function settId($tId){
        self::$tId = $tId;
    }
    
    public static function gettId(){
        return self::$tId;
    }
    
    public static function settIdIn($tIdIn){
        self::$tIdIn = $tIdIn;
    }
    
    public static function gettIdIn(){
        return self::$tIdIn;
    }
}