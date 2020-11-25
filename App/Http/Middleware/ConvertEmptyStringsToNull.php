<?php

/*
 * 重写 Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull
 * null值转化为空值
 * 
 * @auth donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest  as Middleware;

class ConvertEmptyStringsToNull extends Middleware {

    /**
     * 将null值转化为''
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value) {
        return is_null($value) ? '' : $value;
        //return is_string($value) && $value === '' ? null : $value;
    }

}
