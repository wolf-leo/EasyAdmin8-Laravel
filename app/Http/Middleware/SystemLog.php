<?php

namespace App\Http\Middleware;

use App\Http\Services\annotation\ControllerAnnotation;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\SystemLogService;
use App\Http\Services\tool\CommonTool;
use Closure;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
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
        $response = $next($request);
        if (!$request->ajax()) return $response;
        $method = $request->method();
        if (!in_array($method, ['POST', 'PUT', 'DELETE'])) return $response;
        $params = $request->input();
        if (isset($params['s'])) unset($params['s']);
        foreach ($params as $key => $val) {
            in_array($key, $this->sensitiveParams) && $params[$key] = "***********";
        }
        $method = strtolower($method);
        $url    = $request->getPathInfo();
        $title  = '';
        try {
            $pathInfo    = $request->getPathInfo();
            $pathInfoExp = explode('/', $pathInfo);
            $_action     = end($pathInfoExp) ?? '';
            $pathInfoExp = explode('.', $pathInfoExp[2] ?? '');
            $_name       = $pathInfoExp[0] ?? '';
            $_controller = ucfirst($pathInfoExp[1] ?? '');
            if ($_name && $_controller) {
                $className       = "App\Http\Controllers\admin\\{$_name}\\{$_controller}Controller";
                $reflectionClass = new \ReflectionClass($className);
                $parser          = new DocParser();
                $parser->setIgnoreNotImportedAnnotations(true);
                $reader               = new AnnotationReader($parser);
                $controllerAnnotation = $reader->getClassAnnotation($reflectionClass, ControllerAnnotation::class);
                $reflectionAction     = $reflectionClass->getMethod($_action);
                $nodeAnnotation       = $reader->getMethodAnnotation($reflectionAction, NodeAnnotation::class);
                $title                = $controllerAnnotation->title . ' - ' . $nodeAnnotation->title;
            }
        }catch (\Throwable $exception) {
        }
        $ip   = CommonTool::getRealIp();
        $data = [
            'admin_id'    => request()->session()->get('admin.id'),
            'title'       => $title,
            'url'         => $url,
            'method'      => $method,
            'ip'          => $ip,
            'content'     => json_encode($params, JSON_UNESCAPED_UNICODE),
            'response'    => json_encode($response->original, JSON_UNESCAPED_UNICODE),
            'useragent'   => $request->header('HTTP_USER_AGENT'),
            'create_time' => time(),
        ];
        SystemLogService::instance()->save($data);
        return $response;
    }

}
