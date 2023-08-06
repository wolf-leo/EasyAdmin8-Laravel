<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\AdminController;
use App\Models\SystemAdmin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoginController extends AdminController
{
    public function initialize()
    {
        parent::initialize();
        if (\request()->method() == 'GET' && !empty(session('admin')) && $this->action != 'out') {
            $adminModuleName = $this->adminConfig['admin_alias_name'];
            redirect(__url())->send();
        }
    }

    public function index(): View|JsonResponse
    {
        if (!request()->ajax()) {
            $captcha = env('EASYADMIN.CAPTCHA', 1);
            return view('admin.login', compact('captcha'));
        }

        $post      = \request()->post();
        $rules     = [
            'username'   => 'required',
            'password'   => 'required',
            'keep_login' => 'required',
        ];
        $validator = Validator::make($post, $rules, [
            'username' => '用户名不能为空',
            'password' => '密码不能为空或格式错误',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $admin = SystemAdmin::where(['username' => $post['username']])->first();
        if (empty($admin) || password($post['password']) != $admin->password) {
            return $this->error('用户名或密码有误');
        }
        if ($admin->status == 0) {
            return $this->error('账号已被禁用');
        }
        $admin->login_num   += 1;
        $admin->update_time = time();
        $admin->save();
        $admin = $admin->toArray();
        unset($admin['password']);
        $admin['expire_time'] = $post['keep_login'] == 1 ? true : time() + 7200;
        session(compact('admin'));
        return $this->success('登录成功', [], __url());
    }

    public function out(): Response|JsonResponse
    {
        \request()->session()->forget('admin');
        return $this->success('退出登录成功', [], __url('/login'));
    }
}
