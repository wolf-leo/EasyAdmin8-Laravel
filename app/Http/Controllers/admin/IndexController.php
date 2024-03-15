<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\AdminController;
use App\Models\SystemAdmin;
use App\Models\SystemQuick;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use Illuminate\View\View;

class IndexController extends AdminController
{
    public function index(): View
    {
        return $this->fetch();
    }

    /**
     * 后台首页
     * @return View
     */
    public function welcome(): View
    {
        $laravelVersion = app()::VERSION;
        $mysqlVersion   = DB::select("select VERSION() as version")[0]->version ?? '未知';
        $phpVersion     = phpversion();
        $branch         = json_decode(file_get_contents(base_path() . '/composer.json'))->branch ?? 'main';
        $configIsCached = file_exists(base_path() . '/bootstrap/cache/config.php');
        $versions       = compact('laravelVersion', 'mysqlVersion', 'phpVersion', 'branch', 'configIsCached');
        $quicks         = SystemQuick::where('status', 1)->select('id', 'title', 'icon', 'href')->orderByDesc('sort')->limit(8)->get()->toArray();
        return $this->fetch('', compact('quicks', 'versions'));
    }

    /**
     * 修改个人信息
     * @return View|JsonResponse
     */
    public function editAdmin(): View|JsonResponse
    {
        $id    = session('admin.id');
        $model = new SystemAdmin();
        $row   = $model->find($id);
        if (empty($row)) return $this->error('用户信息不存在');
        if (request()->ajax()) {
            if ($this->isDemo) return $this->error('演示环境下不允许修改');
            try {
                $save = updateFields($model, $row);
            }catch (Exception $e) {
                return $this->error('保存失败:' . $e->getMessage());
            }
            return $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }

    public function editPassword(): View|JsonResponse
    {
        $id    = session('admin.id');
        $model = new SystemAdmin();
        $row   = $model->find($id);
        if (empty($row)) return $this->error('用户信息不存在');
        if (request()->ajax()) {
            $post = request()->post();
            if ($this->isDemo) return $this->error('演示环境下不允许修改');
            $rules     = [
                'password'       => 'required',
                'password_again' => 'required',
            ];
            $validator = Validator::make($post, $rules, [
                'password'       => '密码不能为空或格式错误',
                'password_again' => '确认密码不能为空或格式错误',
            ]);
            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }
            if ($post['password'] != $post['password_again']) {
                return $this->error('两次密码输入不一致');
            }
            $newPwd = password($post['password']);
            if ($newPwd == $row->password) return $this->error('新旧密码不能相同');
            try {
                $save = $model->where('id', $id)->update(['password' => $newPwd]);
            }catch (\Exception $e) {
                return $this->error('保存失败');
            }
            if ($save) {
                return $this->success('保存成功');
            }else {
                return $this->error('保存失败');
            }
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }
}
