<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\common\AdminController;
use App\Models\SystemUploadfile;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="上传文件管理")
 */
class UploadfileController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model  = new SystemUploadfile();
        $upload_types = config('admin.upload_types');
        $this->assign(compact('upload_types'));
    }

}
