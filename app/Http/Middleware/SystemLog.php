<?php

namespace App\Http\Middleware;

use App\Http\Services\SystemLogService;
use App\Http\Services\tool\CommonTool;
use Closure;
use Illuminate\Http\Request;

/**
 * 系统操作日志中间件
 * Class SystemLog
 * @package app\admin\middleware
 */
class SystemLog
{

    /**
     * 敏感信息字段，日志记录时需要加密
     * @var array
     */
    protected array $sensitiveParams = [
        'password',
        'password_again',
        'phone',
        'mobile',
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($request->ajax()) {
            $params = $request->input();
            if (isset($params['s'])) unset($params['s']);
            foreach ($params as $key => $val) {
                in_array($key, $this->sensitiveParams) && $params[$key] = "***********";
            }
            $method = strtolower($request->method());
            $url    = $request->getPathInfo();
            if (in_array($method, ['post', 'put', 'delete'])) {
                $ip   = CommonTool::getRealIp();
                $data = [
                    'admin_id'    => request()->session()->get('admin.id'),
                    'url'         => $url,
                    'method'      => $method,
                    'ip'          => $ip,
                    'content'     => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'useragent'   => $_SERVER['HTTP_USER_AGENT'],
                    'create_time' => time(),
                ];
                SystemLogService::instance()->save($data);
            }
        }
        return $next($request);
    }

}
