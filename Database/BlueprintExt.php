<?php

/**
 * BlueprintExt  Blueprint部分方法扩展和重写
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Database;

use Illuminate\Database\Schema\Blueprint;

class BlueprintExt {

    /**
     * 表全局字段，自定义之前执行
     *
     * @param  string  $table
     * @return void
     */
    public static function globalFieldbefore(Blueprint $table) {
        $table->increments('id');
        //$table->uuid('uuid')->nullable(false)->default('')->comment('uuid');
        $table->integer('t_id')->nullable(false)->default(0)->comment('租户id');
        $table->integer('m_id')->nullable(false)->default(0)->comment('应用id');
        $table->integer('ori_id')->nullable(false)->default(0)->comment('应用下溯源id');
    }
    
    /**
     * 表全局字段，自定义之后执行
     *
     * @param  string  $table
     * @return void
     */
    public static function globalFieldafter(Blueprint $table) {
        $table->tinyInteger('state')->nullable(false)->default(0)->comment('审核状态');
        $table->timestamps();
        $table->integer('creator')->nullable(false)->default(0)->comment('创建人id');
        $table->integer('updator')->nullable(false)->default(0)->comment('更新人id');
        $table->char('ip', 15)->nullable(false)->default(0)->comment('操作的ip');
        $table->softDeletes();
    }
    
    /**
     * 增加排序id
     *
     * @param  string  $table
     * @return void
     */
    public static function globalOrderId(Blueprint $table) {
        $table->integer('order_id')->nullable(false)->default(0)->comment('排序id');
        $table->integer('order_extid')->nullable(false)->default(0)->comment('子级排序id');
    }
    
    /**
     * 增加操作ip
     *
     * @param  string  $table
     * @return void
     */
    public static function globalLastIp(Blueprint $table) {
        $table->ipAddress('last_ip')->nullable(false)->default('')->comment('操作ip');
    }

}
