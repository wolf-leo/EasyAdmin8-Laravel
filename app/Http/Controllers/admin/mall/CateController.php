<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;
use App\Models\MallCate;

/**
 * @ControllerAnnotation(title="商品分类管理")
 */
class CateController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new MallCate();
    }

}
