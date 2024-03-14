<?php

namespace App\Http\Controllers\admin\system;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;
use App\Http\Services\curd\BuildCurd;
use App\Http\Services\curd\exceptions\FileException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="CURD可视化管理")
 */
class CurdGenerateController extends AdminController
{

    /**
     * @NodeAnnotation(title="列表")
     */
    public function index(): View
    {
        return $this->fetch();
    }

    /**
     * @NodeAnnotation(title="列表")
     */
    public function save(): Response|JsonResponse|View
    {
        if (!request()->ajax()) return $this->error();
        $type      = request()->input('type', '');
        $tb_prefix = request()->input('tb_prefix', '');
        $tb_name   = request()->input('tb_name', '');
        if (empty($tb_name)) return $this->error('参数错误');
        switch ($type) {
            case "search":
                try {
                    $list = DB::select("show full columns from {$tb_prefix}{$tb_name}");
                    $data = [];
                    foreach ($list as $value) {
                        $data[] = [
                            'name'  => $value->Field,
                            'type'  => $value->Type,
                            'key'   => $value->Key,
                            'extra' => $value->Extra,
                            'null'  => $value->Null,
                            'desc'  => $value->Comment,
                        ];
                    }
                    return $this->success('查询成功', compact('data', 'list'));
                } catch (\PDOException|\Exception $exception) {
                    return $this->error($exception->getMessage());
                }
                break;
            case "add":
                $force = (int)request()->post('force', 0);
                try {
                    $build = (new BuildCurd())->setTablePrefix($tb_prefix)->setTable($tb_name);
                    $build->setForce($force); // 强制覆盖
                    $build    = $build->render();
                    $fileList = $build->getFileList();
                    if (empty($fileList)) return $this->error('这里什么都没有');
                    $result = $build->create();
                    $_file  = $result[0] ?? '';
                    $link   = '';
                    if (!empty($_file)) {
                        $_fileExp      = explode(DIRECTORY_SEPARATOR, $_file);
                        $_fileExp_last = array_slice($_fileExp, -2);
                        $link          = '/' . config('easyadmin.ADMIN', 'admin') . '/' . $_fileExp_last[0] . '.' . Str::snake(explode('Controller.php', end($_fileExp_last))[0] ?? '') . '/index';
                    }
                    return $this->success('生成成功', compact('result', 'link'));
                } catch (FileException $exception) {
                    return json(['code' => -1, 'msg' => $exception->getMessage(), '__token__' => csrf_token()]);
                }
                break;
            case "delete":
                try {
                    $build    = (new BuildCurd())->setTablePrefix($tb_prefix)->setTable($tb_name);
                    $build    = $build->render();
                    $fileList = $build->getFileList();
                    if (empty($fileList)) return $this->error('这里什么都没有');
                    $result = $build->delete();
                    return $this->success('删除自动生成CURD文件成功', compact('result'));
                } catch (FileException $exception) {
                    return json(['code' => -1, 'msg' => $exception->getMessage(), '__token__' => csrf_token()]);
                }
                break;
            default:
                return $this->error('参数错误');
                break;
        }
    }

}
