<?php

/* 
 * 条件筛选类自定义
 * 
 * @author donghc
 * @copyright Copyright (c) 2020 All Rights Reserved
 */

namespace mplo\Model;

use EloquentFilter\ModelFilter as ModelFilterBase;

class ModelFilter extends ModelFilterBase{
    
    protected $drop_id = false;
    
    protected $camel_cased_methods = false;
    
}