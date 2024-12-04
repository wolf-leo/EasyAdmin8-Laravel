@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">

        <form id="app-form" class="layui-form layuimini-form">

            <div class="layui-form-item">
                <label class="layui-form-label required">{{ea_trans('login account')}}</label>
                <div class="layui-input-block">
                    <input type="text" name="username" class="layui-input layui-disabled" readonly value="{{$row['username']}}">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">{{ea_trans('login password')}}</label>
                <div class="layui-input-block">
                    <input type="password" name="password" class="layui-input" lay-verify="required" lay-reqtext="{{ea_trans('please enter the login password')}}" placeholder="{{ea_trans('please enter the login password')}}" value="">
                    <tip>{{ea_trans('please enter the login password')}}</tip>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">{{ea_trans('confirm password')}}</label>
                <div class="layui-input-block">
                    <input type="password" name="password_again" class="layui-input" lay-verify="required" lay-reqtext="{{ea_trans('please enter the confirmation password')}}" placeholder="{{ea_trans('please enter the confirmation password')}}" value="">
                    <tip>{{ea_trans('please enter the confirmation password')}}</tip>
                </div>
            </div>

            <div class="hr-line"></div>
            <div class="layui-form-item text-center">
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>{{ea_trans('confirm',false)}}</button>
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">{{ea_trans('reset',false)}}</button>
            </div>

        </form>

    </div>
</div>
@include('admin.layout.foot')
