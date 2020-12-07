<?php

/*
 * 操作日志类库
 */

namespace mplo\Lib;

use mplo\Lib\Parameter;
use mplo\Lib\Http;
use Illuminate\Support\Facades\Log;

class Oplog {

    public $mId;
    public $attrBefore = null;
    public $attrAfter = null;
    public $changes;

    public function __construct($mId) {
        $this->setmId($mId);
    }

    public function setmId($mId) {
        $this->mId = $mId;
    }

    public function setAttrBefore($attrBefore) {
        $this->attrBefore = !$attrBefore ? null : $attrBefore->getAttributes();
    }

    public function setAttrAfter($attrAfter) {
        if ($attrAfter) {
            $this->attrAfter = $attrAfter->getAttributes();
            $this->changes = $attrAfter->getChanges();
            if ($attrAfter->wasRecentlyCreated) {
                $this->changes = $attrAfter->getAttributes();
            }
        } else {
            $this->attrAfter = null;
            $this->changes = null;
        }
    }

    public function add($optype = '', $ori_id = '', $title = '', $brief = '') {
        $after = [];
        $before = [];

        if ($this->attrAfter) {
            $after = $this->changes;
            $title = $title ? $title : (isset($this->attrAfter['title']) ? $this->attrAfter['title'] : '');
            $ori_id = $ori_id ? $ori_id : (isset($this->attrAfter['id']) ? $this->attrAfter['id'] : '');
        }

        if ($this->attrBefore) {
            if ($after) {
                foreach ($after as $key => $value) {
                    $before[$key] = isset($this->attrBefore[$key]) ? $this->attrBefore[$key] : '';
                }
            } else {
                //attrAfter未被赋值，before等于前面设置的值
                $before = is_null($this->attrAfter) ? $this->attrBefore : [];

                //attrAfter被赋值并且没数值变化，before设置为空
                $before = (!is_null($this->attrAfter) && !$after) ? [] : $before;
            }

            $title = $title ? $title : (isset($this->attrBefore['title']) ? $this->attrBefore['title'] : '');
            $ori_id = $ori_id ? $ori_id : (isset($this->attrBefore['id']) ? $this->attrBefore['id'] : '');
        }

        if (!$optype) {
            list($c, $optype) = Parameter::getControllerActionName();
        }

        if (!$before && !$after) {
            return false;
        }

        $changes = [
            'before' => $before,
            'after' => $after
        ];

        $this->setAttrBefore([]);
        $this->setAttrAfter([]);

        if (class_exists('App\Http\Libs\Oplog')) {
            \App\Http\Libs\Oplog::create($this->mId, $ori_id, $optype, $title, $brief, $changes);
        } else {
            $data = [
                'changes' => [
                    'before' => $before,
                    'after' => $after
                ],
                'optype' => $optype,
                'module_id' => $this->mId,
                'title' => $title,
                'brief' => '',
                'ori_id' => $ori_id,
                'brief' => $brief,
            ];

            //远程访问
            $ret = Http::request(':domain mpAdmin/oplog/create', $data);
            if ($ret['code'] != '0') {
                throw new \Exception('oplog request faild:' . var_export($data, 1));
            }
            
        }
        return true;
    }

}
