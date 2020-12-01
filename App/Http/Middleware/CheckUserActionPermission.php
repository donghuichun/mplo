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

class CheckUserActionPermission {
    
    /**
     * 验证该用户方法入口是否有权限
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        list($controllerName, $actionName) = Parameter::getControllerActionName();
        $ret = Permissions::hasAction(config('app.name'), $controllerName, $actionName);
        
        if(!$ret){
            return Res::Out('', 'WITHOUT_PERMISSIONS');
        }
        
        return $next($request);
    }
    
    

}
