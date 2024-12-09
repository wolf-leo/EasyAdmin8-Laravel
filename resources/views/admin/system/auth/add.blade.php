@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('Permission Name',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input" lay-verify="required" placeholder="{{ea_trans('Please Enter',false)}}" value="">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">{{ea_trans('remark',false)}}</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="{{ea_trans('Please Enter',false)}}"></textarea>
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
