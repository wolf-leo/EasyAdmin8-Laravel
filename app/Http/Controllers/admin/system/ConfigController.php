<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\TriggerService;
use App\Models\SystemConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="系统配置管理")
 */
class ConfigController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new SystemConfig();
    }

    /**
     * @NodeAnnotation(title="列表")
     */
    public function index(): View
    {
        return $this->fetch();
    }

    /**
     * @NodeAnnotation(title="保存")
     */
    public function save(): JsonResponse
    {
        if (!request()->ajax()) return $this->error();
        $post = request()->post();
        try {
            foreach ($post as $key => $val) {
                if (in_array($key, ['file', 'files'])) continue;
                $this->model->where('name', $key)->update(['value' => $val,]);
            }
            TriggerService::updateSysconfig();
        } catch (\Exception $e) {
            return $this->error('保存失败:' . $e->getMessage());
        }
        return $this->success('保存成功');
    }

}
