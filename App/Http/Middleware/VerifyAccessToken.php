<?php

/**
 * VerifyAccessToken jwt token验证
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use mplo\Lib\Res;
use mplo\Lib\Redis;
use mplo\Lib\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facade;
use mplo\Lib\Http;

class VerifyAccessToken {

    public $accessToken = '';
    public $accessUserId = '';
    public $accessTId = '';
    public $accessTIdIn = '';
    public $accessApiUniqueToken = '';

    /**
     * 验证access_token是否合法，并解析
     * jwt token
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $this->accessToken = $request->header('Access-Token') ? $request->header('Access-Token') : $request->access_token;
        $this->accessUserId = $request->header('Access-User-Id') ? $request->header('Access-User-Id') : $request->access_user_id;
        $this->accessTId = $request->header('Access-T-Id') ? $request->header('Access-T-Id') : $request->access_t_id;
        $this->accessTIdIn = $request->header('Access-T-Id-In') ? $request->header('Access-T-Id-In') : $request->access_t_id_in;
        $this->accessApiUniqueToken = $request->header('Access-Api-Unique-Token') ? $request->header('Access-Api-Unique-Token') : $request->access_api_unique_token;


        //用户信息，先赋值，再验证，request需要获取令牌
        Auth::setUserId($this->accessUserId);
        Auth::settId($this->accessTId);
        Auth::settIdIn($this->accessTIdIn);
        Auth::setaccessToken($this->accessToken);

        $ret = Http::request(":domain mpAdmin/auth/user");
//        print_r($ret);exit;
        if ($ret['code'] != '0') {
            if ($ret['code'] == '1001') {
                $code = 'AUTH_FAILD';
            }else if ($ret['code'] == '1002') {
                $code = 'TOKEN_EXPIRED';
            }else{
                $code = 'AUTH_FAILD';
            }
            return Res::Out('', $code);
        }

        return $next($request);
    }

}
