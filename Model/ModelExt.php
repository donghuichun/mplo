<?php

/*
 * model 继承自定义
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use mplo\Lib\Parameter;
use mplo\Lib\Auth;

class ModelExt extends Model {

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        $this->creator = Auth::$userId;
        $this->t_id = Auth::$tIdIn;
        $this->m_id = 2;
    }

    /**
     * 模型的 "booted" 方法
     *
     * @return void
     */
    protected static function booted() {
        static::addGlobalScope('t_id', function (Builder $builder) {
            $builder->where('t_id', '=', Auth::$tIdIn);
        });
    }

    /**
     * 验证参数与model设置的参数验证是否通过，失败返回错误信息
     * @param array $params
     * @param array $addRuleFields 增加验证的字段规则
     * @param array $delRuleFields 去除验证的字段规则
     * @param array $onlyRuleFields 只验证这个数组里的字段规则
     * @return boolean|string
     */
    public function checkModelParams($params, $addRuleFields = [], $delRuleFields = [], $onlyRuleFields = []) {
        //默认参数里加上全局必要参数
        $params = array_merge($params, array('t_id' => $this->t_id,
            'creator' => $this->creator,
            'm_id' => $this->m_id));
        
        if ($addRuleFields) {
            $this->validate['rules'] = array_merge($this->validate['rules'], $addRuleFields);
        }
        if ($delRuleFields) {
            foreach ($delRuleFields as $delRuleField) {
                unset($this->validate['rules'][$delRuleField]);
            }
        }
        if ($onlyRuleFields) {
            $newRules = [];
            foreach ($onlyRuleFields as $onlyRuleField) {
                $newRules[$onlyRuleField] = $this->validate['rules'][$onlyRuleField];
            }
            $this->validate['rules'] = $newRules;
        }

        return Parameter::checkParams($params, $this->validate['rules'], $this->validate['messages'], $this->validate['custom']);
    }
    

}
