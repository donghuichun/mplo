<?php

/**
 * 数据处理
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Lib;

use Validator;

class Parameter {

    /**
     * 数据值验证
     * 
     * @author donghc
     * @param array $params 验证的数据：['title' => 'i am title', 'brief' => 'i am brief']
     * @param array $rules 验证的规则：['title' => ['required', 'string']]
     * @param array $messages 错误码信息：['required' => ':attribute为必填项']
     * @param array $custom 字段名称：['title' => '标题']
     * @return string | bool
     */
    public static function checkParams($params, $rules, $messages = [], $custom = []) {
        if(!$params){
            return true;
        }
        $validator = Validator::make($params, $rules, $messages, $custom);
        if ($validator->fails()) {
            $requestValidateResult = '';
            //验证错误的信息进行字符串拼接后返回
            foreach ($validator->errors()->getMessages() as $msg) {
                $requestValidateResult .= implode(',', $msg) . "; ";
            }
            return $requestValidateResult;
        }
        return true;
    }
    
    /**
     * 获取访问的控制器名称、方法名称
     * @param boolen $controllerAll 是否返回控制器名称全称
     * @return array
     */
    public static function getControllerActionName($controllerAll = false){
        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $actionName) = explode('@', $controller); 
        $controllerName = $controllerAll ? $controller : lcfirst(substr($controller, 0, strrpos($controller, "Controller")));
        return [$controllerName, $actionName];
    }

    

}
