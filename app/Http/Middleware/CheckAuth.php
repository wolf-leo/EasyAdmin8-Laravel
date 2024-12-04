<?php

namespace App\Http\Middleware;

use App\Http\Controllers\admin\ErrorPageController;
use App\Http\JumpTrait;
use App\Http\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    use JumpTrait;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminConfig = config('admin');
        $parameters  = request()->route()->parameters;
        $controller  = $parameters['controller'] ?? 'index';
        $adminId     = session('admin.id', 0);
        if (!in_array($controller, $adminConfig['no_login_controller'])) {
            $expireTime = session('admin.expire_time');
            if (empty($adminId)) {
                return $this->responseView(ea_trans('Please log in to the backend first', false), [], __url("/login"));
            }
            // 判断是否登录过期
            if ($expireTime !== true && time() > $expireTime) {
                $request->session()->forget('admin');
                return $this->responseView(ea_trans('Login has expired, please log in again', false), [], __url("/login"));
            }
        }
        // 验证权限
        if ($adminId) {
            $authService = app(AuthService::class, ['adminId' => $adminId]);
            $currentNode = $authService->getCurrentNode();
            if (!in_array($controller, $adminConfig['no_auth_controller']) && !in_array($controller, $adminConfig['no_auth_node'])) {
                $check = $authService->checkNode($currentNode);
                if (!$check) return (request()->ajax() || request()->method() == 'POST') ? $this->error(ea_trans('Unauthorized access', false)) : $this->responseView(ea_trans('Unauthorized access', false));
                // 判断是否为演示环境
                if (config('easyadmin.IS_DEMO', false) && \request()->method() == 'POST') {
                    if (!in_array($currentNode, ['system.log/record', ''])) return (request()->ajax() || request()->method() == 'POST') ? $this->error(ea_trans('Modification is not allowed in the demonstration environment', false)) : $this->responseView(ea_trans('Unauthorized access', false));
                }
            }
        }
        return $next($request);
    }
}
