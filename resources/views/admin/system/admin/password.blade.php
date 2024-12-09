@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">

        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('login account',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" readonly value="{{$row['username']}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('login password',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input" lay-verify="required" placeholder="{{ea_trans('Please Enter',false)}}" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('confirm password',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="password" name="password_again" class="layui-input" lay-verify="required" placeholder="{{ea_trans('Please Enter',false)}}" value="">
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
