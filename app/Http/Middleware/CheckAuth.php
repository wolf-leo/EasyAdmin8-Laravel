<?php

namespace App\Http\Middleware;

use App\Http\Controllers\admin\ErrorPageController;
use App\Http\JumpTrait;
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
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminConfig = config('admin');
        $parameters  = request()->route()->parameters;
        $controller  = $parameters['controller'] ?? 'index';
        if (!in_array($controller, $adminConfig['no_login_controller'])) {
            $adminId    = session('admin.id');
            $expireTime = session('admin.expire_time');
            if (empty($adminId)) {
                return $this->responseView('请先登录后台', [], __url("/login"));
            }
            // 判断是否登录过期
            if ($expireTime !== true && time() > $expireTime) {
                $request->session()->forget('admin');
                return $this->responseView('登录已过期，请重新登录', [], __url("/login"));
            }
        }
        return $next($request);
    }
}
