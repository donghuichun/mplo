<?php
/**
 * FormatInput 初始并格式部分全局数据
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FormatInput {
    
    /**
     * 初始并格式部分全局数据
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        
        $request->offset = intval($request->offset);
        request()->offsetSet('offset', intval($request->offset));
        
        $request->count = ($request->count=intval($request->count)) ? $request->count : 20;
        request()->offsetSet('count', ($request->count=intval($request->count)) ? $request->count : 20);

        $request->parent_id = empty($request->parent_id) ? '' : intval($request->parent_id);
        request()->offsetSet('parent_id', $request->parent_id);
        
        return $next($request);
    }
    
    

}
