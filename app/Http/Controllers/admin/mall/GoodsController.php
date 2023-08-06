<?php

namespace App\Http\Controllers\admin\mall;

use App\Http\Controllers\common\AdminController;
use App\Http\Services\annotation\NodeAnnotation;
use App\Http\Services\annotation\ControllerAnnotation;
use App\Models\MallGoods;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * @ControllerAnnotation(title="商城商品管理")
 */
class GoodsController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new MallGoods();
    }

    /**
     * @NodeAnnotation(title="入库")
     */
    public function stock(): View|JsonResponse
    {
        $id  = request()->input('id');
        $row = $this->model->find($id);
        if (empty($row)) return $this->error('数据不存在');
        if (request()->ajax()) {
            $post = request()->post();
            try {
                $params['total_stock'] = $row->total_stock + $post['stock'];
                $params['stock']       = $row->stock + $post['stock'];
                $save                  = updateFields($this->model, $row, $params);
            } catch (\Exception $e) {
                return $this->error('保存失败');
            }
            return $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign(compact('row'));
        return $this->fetch();
    }
}
