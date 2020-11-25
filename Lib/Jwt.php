<?php

/*
 * jwt
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Lib;

use Firebase\JWT\JWT as Fjwt;
use Faker\Provider\Uuid;

class Jwt {

    /**
     * 鉴权生成
     * @param array $data 传输数据
     * @return array access_token, expire_time, unique_token(唯一)
     */
    public static function encode(array $data, $uuid = '') {
        $key = config('app.key');
        $nowtime = time();
        $payload = array(
            "iss" => "", //jwt签发者
            "aud" => "", //接收jwt的一方
            'sub' => '', //jwt所面向的用户
            'exp' => $nowtime + config('jwt.exp_seconds'), //jwt的过期时间，这个过期时间必须要大于签发时间
            "iat" => $nowtime, //jwt的签发时间
            "nbf" => config('jwt.nbf'), //定义在什么时间之前，该jwt都是不可用的
            'jti' => $uuid ? $uuid : Uuid::uuid(), //jwt的唯一身份标识，主要用来作为一次性token,从而回避重放攻击
            'data' => $data //存放的业务数据
        );
        $jwt = Fjwt::encode($payload, $key);

        return ['access_token' => $jwt, 'expire_time' => $payload['exp'], 'unique_token' => $payload['jti'], 'data' => $data];
    }

    /**
     * 鉴权验证
     * @param type $iss 模块标识，需要在env文件里定义
     * @param type $jwt 鉴权token
     * @return array
     * @throws \Exception
     */
    public static function decode($token) {
        $key = config('app.key');
        FjWT::$leeway = config('jwt.leeway'); // $leeway in seconds
        try{
            $decoded = FjWT::decode($token, $key, array('HS256'));
            $ret['code'] = 'ok';
            $ret['unique_token'] = $decoded->jti;
            $ret['data'] = (array)$decoded->data;
        } catch (\Exception $ex) {
            $ret['code'] = $ex->getmessage();
            $ret['data'] = '';
        }

        return $ret;
    }
    
    /**
     * 根据过期token获取新的token
     * @param type $token 原有token
     * @return array access_token, expire_time, unique_token(唯一)
     * @throws \Exception
     */
    public static function encodeReplace($token)
    {
        //解析原有token，得到里面值
        $payload = self::decodeExp($token);
        if(!$payload){
            return false;
        }
        
        //生成新token
        return self::encode((array)$payload->data, $payload->jti);
    }
    
    /**
     * 解密token
     * @param type $token 原有token
     * @return object
     * @throws \Exception
     */
    public static function decodeExp($token)
    {
        //先验证access_token是否合规
        $checkRet = self::decode($token);
        if($checkRet['code'] != 'Expired token'){
            return false;
        }
        
        $key = config('app.key');
        
        if (empty($key)) {
            return false;
        }
        $tks = \explode('.', $token);
        if (\count($tks) != 3) {
            return false;
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;

        if (null === $payload = FjWT::jsonDecode(FjWT::urlsafeB64Decode($bodyb64))) {
            return false;
        }
        
        return $payload;
    }

}
