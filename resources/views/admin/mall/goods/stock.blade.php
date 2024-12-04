@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('goods name',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input layui-disabled" disabled value="{{$row['title']}}" readonly>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('inventory statistics',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input layui-disabled" disabled value="{{$row['total_stock']}}" readonly>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('remaining inventory',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input layui-disabled" disabled value="{{$row['stock']}}" readonly>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('inventory quantity',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="number" name="stock" class="layui-input" lay-verify="required" placeholder="{{ea_trans('please enter',false)}}" value="0">
            </div>
        </div>

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>{{ea_trans('confirm',false)}}</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">{{ea_trans('reset',false)}}</button>
        </div>

    </form>
</div>
@include('admin.layout.foot')
