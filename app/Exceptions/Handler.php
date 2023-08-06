<?php

namespace App\Exceptions;

use App\Http\JumpTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    use JumpTrait;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (\Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $e)
    {
        if (!is_file(base_path() . DIRECTORY_SEPARATOR . '.env')) {
            return $this->error('.env 文件不存在');
        }

        $appKey = env('APP_KEY', '');
        if (empty($appKey)) {
            return $this->error('请先设置 APP_KEY , 通过命令: php artisan key:generate');
        }

        //系统默认错误
        if (config('app.debug')) {
            return parent::render($request, $e);
        }
    }
}
