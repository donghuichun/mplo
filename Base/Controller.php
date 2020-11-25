<?php

/**
 * controller基类，全局处理
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Base;

use Illuminate\Routing\Controller as BaseController;
use mplo\Lib\Res;
use mplo\Lib\Parameter;

class Controller extends BaseController
{
    
    /**
     * 格式化接口输出信息
     * 
     * @author donghc
     * @param [string,array] $data 接口返回的数据
     * @param string $code 接口返回的错误码变量，记录在mplo/conf/code.php+/config/code.php
     * @param string $msg 错误码信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function Out($data = [], $code = '', $msg = '')
    {
        return Res::Out($data, $code, $msg);
    }
    
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
    public function checkParams($params, $rules, $messages = [], $custom = [])
    {   
        return Parameter::checkParams($params, $rules, $messages, $custom);
    }
}
