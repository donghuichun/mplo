<?php

namespace mplo\Base;

use Illuminate\Foundation\Validation\ValidatesRequests;
use mplo\Base\Controller as BaseController;

class Controller extends BaseController
{
    use ValidatesRequests;
    
    public function hello()
    {
        return 'ok';
    }
}
