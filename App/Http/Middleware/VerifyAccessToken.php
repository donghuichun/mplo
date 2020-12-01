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
use mplo\Lib\Jwt;
use mplo\Lib\Res;
use mplo\Lib\Redis;
use mplo\Lib\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\Debugbar\Facade;

class VerifyAccessToken {
    
    /**
     * 验证access_token是否合法，并解析
     * jwt token
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $accessToken = $request->header('Access-Token') ? $request->header('Access-Token') : $request->access_token;
        $accessUserId = $request->header('Access-User-Id') ? $request->header('Access-User-Id') : $request->access_user_id;
        $accessTId = $request->header('Access-T-Id') ? $request->header('Access-T-Id') : $request->access_t_id;
        $accessTIdIn = $request->header('Access-T-Id-In') ? $request->header('Access-T-Id-In') : $request->access_t_id_in;
        $accessApiUniqueToken = $request->header('Access-Api-Unique-Token') ? $request->header('Access-Api-Unique-Token') : $request->access_api_unique_token;
        
        $tokeArr = Jwt::decode($accessToken);
        if ($tokeArr['code'] != 'ok') {
            if ($tokeArr['code'] == 'Expired token') {
                return Res::Out('', 'TOKEN_EXPIRED');
            }
            return Res::Out('', 'AUTH_FAILD');
        }
        
        /*
         * 验证缓存有无过期
         * 如果账号只能一个浏览器登录，则缓存需查找：user_id=unqiue_token
         */
        if (config('jwt.login_only_one')) {
            $loginUserUniqueToken = Redis::get(config('cachekey.Slogin_token') . $tokeArr['data']['user_id']);
            if (!$loginUserUniqueToken || $loginUserUniqueToken != $tokeArr['unique_token']) {
                return Res::Out('', 'AUTH_FAILD');
            }
            
            //更新缓存时间
            Redis::expire(config('cachekey.Slogin_token') . $tokeArr['data']['user_id'], config('jwt.login_access_token_exp'));;;
        } else {
            $loginUserUniqueToken = Redis::get(config('cachekey.Slogin_token') . $tokeArr['unique_token']);
            if (!$loginUserUniqueToken) {
                return Res::Out('', 'AUTH_FAILD');
            }
            
            //更新缓存时间
            Redis::expire(config('cachekey.Slogin_token') . $tokeArr['unique_token'], config('jwt.login_access_token_exp'));
        }
        
        //验证传入的user_id、t_id、t_id_in是否与解密token相等，防止参数恶意修改
        if($accessUserId!=$tokeArr['data']['user_id'] || $accessTId!=$tokeArr['data']['t_id'] || $accessTIdIn!=$tokeArr['data']['t_id_in']){
            return Res::Out('', 'AUTH_FAILD');
        }
        
        //用户信息
        Auth::setUserId($tokeArr['data']['user_id']);
        Auth::settId($tokeArr['data']['t_id']);
        Auth::settIdIn($tokeArr['data']['t_id_in']);
        
        return $next($request);
    }
    
    

}
