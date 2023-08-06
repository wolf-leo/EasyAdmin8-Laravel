<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\common\AdminController;
use App\Models\SystemQuick;
use App\Http\services\annotation\NodeAnnotation;
use App\Http\services\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="快捷入口管理")
 */
class QuickController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new SystemQuick();
    }

}
