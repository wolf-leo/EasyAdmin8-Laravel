<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\AdminController;
use App\Http\services\MenuService;
use App\Http\services\UploadService;
use App\Models\SystemUploadfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AjaxController extends AdminController
{
    /**
     * @desc 初始化导航
     * @return JsonResponse
     */
    public function initAdmin(): JsonResponse
    {
        $cacheData = Cache::get('initAdmin_' . session('admin.id'));
        if (!empty($cacheData)) {
            return json($cacheData);
        }
        $menuService = new MenuService(session('admin.id'));
        $data        = [
            'logoInfo' => [
                'title' => sysconfig('site', 'logo_title'),
                'image' => sysconfig('site', 'logo_image'),
                'href'  => __url(),
            ],
            'homeInfo' => $menuService->getHomeInfo(),
            'menuInfo' => $menuService->getMenuTree(),
        ];
        Cache::put('initAdmin_' . session('admin.id'), $data);
        return json($data);
    }

    /**
     * @desc 清理缓存接口
     * @return JsonResponse
     */
    public function clearCache(): JsonResponse
    {
        Cache::flush();
        return $this->success('清理缓存成功');
    }

    /**
     * @desc  上传文件
     * @return View|JsonResponse
     */
    public function upload(): View|JsonResponse
    {
        if ($this->isDemo) return $this->error('演示环境下不允许修改');
        if (request()->method() != 'POST') return $this->error();
        $type         = request()->input('type', '');
        $data         = [
            'upload_type' => request()->post('upload_type', ''),
            'file'        => request()->file($type == 'editor' ? 'upload' : 'file'),
        ];
        $uploadConfig = sysconfig('upload');
        empty($data['upload_type']) && $data['upload_type'] = $uploadConfig['upload_type'];
        $rules     = [
            'upload_type' => ['required', Rule::in(explode(',', $uploadConfig['upload_allow_type']))],
            'file'        => 'required',
        ];
        $validator = Validator::make($data, $rules, [
            'upload_type' => '指定上传类型有误',
            'file'        => '文件不能为空',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $file = $type == 'editor' ? request()->upload : request()->file;
        if (!in_array($file->extension(), explode(',', $uploadConfig['upload_allow_ext']))) {
            return $this->error('上传文件类型不在允许范围');
        }
        if ($file->getSize() > $uploadConfig['upload_allow_size']) {
            return $this->error('文件大小超过预设值');
        }
        $upload_type = $uploadConfig['upload_type'];
        try {
            $upload = UploadService::instance()->setConfig($uploadConfig)->$upload_type($file, $type);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        $code = $upload['code'] ?? 0;
        if ($code == 0) {
            return $this->error($upload['data'] ?? '');
        } else {
            return $type == 'editor' ? json(
                [
                    'error'    => ['message' => '上传成功', 'number' => 201,],
                    'fileName' => '',
                    'uploaded' => 1,
                    'url'      => $upload['data']['url'] ?? '',
                ]
            ) : $this->success('上传成功', $upload['data'] ?? '');
        }
    }

    /**
     * @desc 获取上传文件
     * @return JsonResponse
     */
    public function getUploadFiles(): JsonResponse
    {
        $get         = request()->input();
        $limit       = $get['limit'] ?? 10;
        $title       = $get['title'] ?? '';
        $this->model = new SystemUploadfile();
        $where       = [];
        if ($title) $where[] = ['original_name', 'LIKE', "%{$title}%"];
        $count = $this->model->where($where)->count();
        $list  = $this->model->where($where)->orderByDesc($this->order)->paginate($limit)->items();
        $data  = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return json($data);
    }
}
