<?php

/**
 * 数据输出返回处理
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Lib;

class Res {

    //错误码key名称
    public static $keycode = 'code';
    //错误码信息key名称
    public static $keymsg = 'msg';
    //输入内容key名称
    public static $keydata = 'data';

    /**
     * 格式化接口输出信息
     * 
     * @author donghc
     * @param [string,array] $data 接口返回的数据
     * @param string $code 接口返回的错误码变量，默认为OK，记录在mplo/conf/code.php+/config/code.php
     * @param string $msg 错误码信息
     * @return \Illuminate\Http\JsonResponse
     */
    public static function Out($data = [], $code = 'OK', $msg = '') {
        $codeData = self::get_msg($code);
        self::parseNull($data);
        return response()->json([
                    self::$keycode => $codeData['code'],
                    self::$keymsg => $msg ? $msg : $codeData['msg'],
                    self::$keydata => $data,
        ]);
    }

    /**
     * 根据错误码变量获取错误码信息
     * 
     * @author donghc
     * @param string $code 接口返回的错误码变量，记录在mplo/conf/code.php+/config/code.php
     * @return array
     */
    public static function get_msg($code) {
        $data = require __DIR__ . '/../Conf/Code.php';
        $moduledata = require config_path() . '/Code.php';
        $data = array_merge($data, $moduledata);
        if (isset($data[$code])) {
            return $data[$code];
        } else {
            return $data['OK'];
        }
    }

    /**
     * 将值为null的更换为""
     * 
     * @author donghc
     * @param string,array $data 处理的变量
     * @return void
     */
    public static function parseNull(&$data) {
        if (is_array($data)) {
            foreach ($data as &$v) {
                self::parseNull($v);
            }
        } else {
            if (is_null($data)) {
                $data = "";
            }
        }
    }

}
