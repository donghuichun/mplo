<?php
/**
 * CheckUserActionPermission 用户操作方法入口的权限认证
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use mplo\Lib\Res;
use mplo\Lib\Parameter;
use mplo\Lib\Permissions;
use mplo\Lib\Http;

class CheckUserActionPermission {
    
    /**
     * 验证该用户方法入口是否有权限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        
        //如果没有获取并赋值过权限数据，则请求获取初始化
        if(!Permissions::getPermission()){
            $permissions = Http::request(':domain mpAdmin/permission/show')['data'];
            Permissions::setPermission($permissions); 
        }
        
        list($controllerName, $actionName) = Parameter::getControllerActionName();
        $ret = Permissions::hasAction(config('app.name'), $controllerName, $actionName);
        
        if(!$ret){
            return Res::Out('', 'WITHOUT_PERMISSIONS');
        }
        
        return $next($request);
    }
    
    

}
