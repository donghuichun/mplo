<?php

/* 
 * Permissions 验证权限
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */
namespace mplo\Lib;

use mplo\Lib\Redis;

class Permissions{
    
    //权限数据
    public static $permission;
    
    /**
     * 设置权限数据
     * 
     * @param type $data
     * return void
     */
    public static  function setPermission($data){
        self::$permission = $data;
    }
    
    /**
     * 获取权限数据
     * 
     * return array
     */
    public static function getPermission(){
        return self::$permission;
    }
    
    /**
     * 判断访问方法是否有权限
     * sys_allocates=0 需要进行权限验证
     * sys_allocates=1 系统分配的最高级角色，无需验证权限
     * sys_allocates=2 系统分配的普通角色，该角色不能删除，但需要进行权限验证
     * 
     * @param type $projectName 工程名称
     * @param type $controllerName 控制器名称，如：user
     * @param type $actionName 方法名
     * @return boolean
     */
    public static function hasAction($projectName, $controllerName, $actionName){
        if(self::$permission['sys_allocates']=='1'){
            return true;
        }
        $key = $projectName .'_'. $controllerName .'_'. $actionName;
        if(isset(self::$permission['actions'][$key])){
            return true;
        }
        return false;
    }
    
    /**
     * 获取权限数据
     * 
     * @return type
     */
    public function getPermissions(){
        if(self::$permissions){
            return ;
        }
        
        //读取缓存
        //Redis::setex(config('cachekey.Slogin_token') . $tokeArr['unique_token'], config('jwt.login_access_token_exp'), 1);
        
        //访问mpAdmin获取该用户权限配置
        
        //保存redis，过期时间设置为5分钟
        
        
    }
    
}