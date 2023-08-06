<?php

namespace App\Http;

use App\Http\Services\tool\CommonTool;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;
use jianyan\excel\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

/**
 * 后台CURD复用
 * Trait Curd
 * @package app\admin\traits
 */
trait Curd
{

    /**
     * @NodeAnotation(title="列表")
     */
    public function index(): View|JsonResponse
    {
        if (!request()->ajax()) return $this->fetch();
        if (request()->input('selectFields')) {
            return $this->selectList();
        }
        list($page, $limit, $where) = $this->buildTableParams();
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

    /**
     * @NodeAnotation(title="添加")
     */
    public function add(): View|JsonResponse
    {
        if (request()->ajax()) {
            try {
                $save = insertFields($this->model);
            } catch (\Exception $e) {
                return $this->error('保存失败:' . $e->getMessage());
            }
            return $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit(): View|JsonResponse
    {
        $id  = (int)request()->input('id');
        $row = $this->model->find($id);
        if (empty($row)) return $this->error('数据不存在');
        if (request()->ajax()) {
            try {
                $save = updateFields($this->model, $row);
            } catch (\PDOException | \Exception $e) {
                return $this->error('保存失败:' . $e->getMessage());
            }
            return $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="删除")
     */
    public function delete(): JsonResponse
    {
        if (!request()->ajax()) return $this->error();
        $id = request()->input('id');
        if (!is_array($id)) $id = (array)$id;
        $row = $this->model->whereIn('id', $id)->get()->toArray();
        if (empty($row)) return $this->error('数据不存在');
        try {
            $save = $this->model->whereIn('id', $id)->delete();
        } catch (\PDOException | \Exception $e) {
            return $this->error('删除失败:' . $e->getMessage());
        }
        return $save ? $this->success('删除成功') : $this->error('删除失败');
    }

    /**
     * @NodeAnotation(title="导出")
     */
    public function export(): View|bool
    {
        list($page, $limit, $where) = $this->buildTableParams();
        $tableName = $this->model->getTable();
        $tableName = CommonTool::humpToLine(lcfirst($tableName));
        $prefix    = config('database.connections.mysql.prefix');
        $dbList    = DB::select("show full columns from {$prefix}{$tableName}");
        $header    = [];
        foreach ($dbList as $vo) {
            $comment = !empty($vo->Comment) ? $vo->Comment : $vo->Field;
            if (!in_array($vo->Field, $this->noExportFields)) {
                $header[] = [$comment, $vo->Field];
            }
        }
        $list = $this->model->where($where)->limit(100000)->orderByDesc('id')->get();
        if (empty($list)) return $this->error('暂无数据');
        $list     = $list->toArray();
        $fileName = time();
        try {
            return Excel::exportData($list, $header, $fileName, 'xlsx');
        } catch (Exception | \PhpOffice\PhpSpreadsheet\Exception$e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify(): JsonResponse
    {
        if (!request()->ajax()) return $this->error();
        $post      = request()->post();
        $rules     = [
            'id'    => 'required',
            'field' => 'required',
            'value' => 'required',
        ];
        $validator = Validator::make($post, $rules, [
            'id'    => 'ID不能为空',
            'field' => '字段不能为空',
            'value' => '值不能为空',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $row = $this->model->find($post['id']);
        if (empty($row)) {
            return $this->error('数据不存在');
        }
        try {
            foreach ($post as $key => $item) if ($key == 'field') $row->$item = $post['value'];
            $row->save();
        } catch (\PDOException | \Exception $e) {
            return $this->error("操作失败:" . $e->getMessage());
        }
        return $this->success('保存成功');
    }

}
