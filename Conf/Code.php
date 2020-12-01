<?php

/* 
 * 全局错误码配置
 */

return [
    
    'OK' => array('code'=>'0', 'msg'=>'请求成功'),
    'NOT_LOGIN' => array('code'=>'1001', 'msg'=>'未登陆或者登录超时'),
    'AUTH_FAILD' => array('code'=>'1001', 'msg'=>'鉴权失败，缺少参数或者参数异常'),
    'TOKEN_EXPIRED' => array('code'=>'1002', 'msg'=>'登录过期'),
    'PARAMS_NOT_COMPLETE' => array('code'=>'1003', 'msg'=>'缺少参数或者参数异常'),
    'NO_DETAIL' => array('code'=>'1004', 'msg'=>'未查询到数据'),
    'LOGIN_FAILD' => array('code'=>'1005', 'msg'=>'登录失败'),
    'FILE_NOT_EXISTS' => array('code'=>'1006', 'msg'=>'文件不存在'),
    'FAILD' => array('code'=>'1007', 'msg'=>'处理失败'),
    'WITHOUT_PERMISSIONS' => array('code'=>'1008', 'msg'=>'没有权限'),
    
    'PASSWORD_VALIDATION_FAILD' => array('code'=>'1030', 'msg'=>'密码必须包含英文字母、数字、符号，长度为8-30位'),
    'PASSWORD_EQUAL_USERNAME' => array('code'=>'1031', 'msg'=>'密码与用户名相同'),
    
];
