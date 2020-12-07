<?php

/*
 * 外部http请求
 */

namespace mplo\Lib;

use Illuminate\Support\Facades\Http as HttpBase;
use mplo\Lib\Auth;
use mplo\Lib\Permissions;
use Illuminate\Support\Facades\Log;

class Http {

    public static function getheader() {
        return [
            'Access-Token' => Auth::$accessToken,
            'Access-User-Id' => Auth::$userId,
            'Access-T-Id' => Auth::$tId,
            'Access-T-Id-In' => Auth::$tIdIn,
            'Access-Api-Unique-Token' => '',
        ];
    }

    public static function url($url) {
        $url = preg_replace_callback("/:domain (\w+)\/(.*)/i", function($matches) {
            $app = Permissions::getApp(true);
            return rtrim($app[$matches[1]]['domain'], '/') . '/' . $matches[2];
        }, $url);
        return $url;
    }

    public static function request(string $url, array $params = [], $method = 'get', array $header = [], string $returnFormat = 'json', bool $requestInternal = true) {

        //url处理
        $url = self::url($url);

        if ($requestInternal) {
            $header = array_merge($header, self::getheader());
        }
        Log::info('request:'.$url. var_export($params, 1));
        if ($method == 'get') {
            $response = HttpBase::withHeaders($header)->get($url, $params);
        } else {
            $response = HttpBase::withHeaders($header)->post($url, $params);
        }
        if (!$response->ok()) {
            $response->throw();
        }
        Log::info('request result:'.$url. $response->body());

        if ($returnFormat == 'json') {
            $ret = $response->json();
        } else {
            $ret = $response->body();
        }
        return $ret;
    }

}
