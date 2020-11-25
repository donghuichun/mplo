<?php

namespace mplo\Base;

//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use mplo\Base\Controller as BaseController;

class ControllerBack extends BaseController {

    use DispatchesJobs, ValidatesRequests;

}
