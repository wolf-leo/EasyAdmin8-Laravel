<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;
use App\Models\MallGoods;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="Mall Product Management")
 */
class GoodsController extends AdminController
{
    /**
     * 过滤不需要生成的权限节点 默认 CURD 中会自动生成部分节点 可以在此处过滤
     * @var array[]
     */
    protected array $ignoreNode = ['export'];

    public function initialize()
    {
        parent::initialize();
        $this->model = new MallGoods();
    }

    /**
     * @NodeAnnotation(title="list")
     */
    public function index(): View|JsonResponse
    {
        if (!request()->ajax()) return $this->fetch();
        list($page, $limit, $where) = $this->buildTableParams();
        $count = $this->model->where($where)->count();
        $list  = $this->model->where($where)->with(['cate'])->orderByDesc($this->order)->paginate($limit)->items();
        $data  = [
            'code'  => 0,
            'msg'   => '',
            'count' => $count,
            'data'  => $list,
        ];
        return json($data);
    }

    /**
     * @NodeAnnotation(title="stock")
     */
    public function stock(): View|JsonResponse
    {
        $id  = request()->input('id');
        $row = $this->model->find($id);
        if (empty($row)) return $this->error(ea_trans('data does not exist', false));
        if (request()->ajax()) {
            $post = request()->post();
            try {
                $params['total_stock'] = $row->total_stock + $post['stock'];
                $params['stock']       = $row->stock + $post['stock'];
                $save                  = updateFields($this->model, $row, $params);
            }catch (\Exception $e) {
                return $this->error(ea_trans('operation failed', false));
            }
            return $save ? $this->success(ea_trans('operation successful', false)) : $this->error(ea_trans('operation failed', false));
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }
}
