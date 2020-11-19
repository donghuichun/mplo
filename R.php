<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace mpyii;

use Yii;
use yii\web\Response;

class R {

    /**
     * api输出
     * @param type $data
     * @param type $code
     * @param type $msg
     * @return array
     */
    public static function Out($data = array(), $code = 'OK', $msg = '') {
        //处理返回值为null置换为空
        mpReplaceNullToEmpty($data);

        if ($code) {
            $errorCode = require Yii::getAlias("@app") . '/config/errorCode.php';
            $moduleErrorCode = require Yii::getAlias("@app") . "/config/errorCode.php";
            $errorCode = $moduleErrorCode + $errorCode;
            $code = isset($errorCode[$code]) ? $errorCode[$code] : $code;
        }

        $ret['code'] = $code['code'];
        $ret['message'] = $msg ? $msg : $code['msg'];
        $ret['data'] = $data;
        //$ret['csrfToken'] = Yii::$app->request->csrfToken;
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSONP;
            Yii::$app->response->data = ['data' => $ret, 'callback' => 'callback'];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $ret;
        }
        return Yii::$app->response->data;
    }

}
