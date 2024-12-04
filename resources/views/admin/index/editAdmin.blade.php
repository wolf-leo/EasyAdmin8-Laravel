@include('admin.layout.head')
<div class="layuimini-container">
    <div class="layuimini-main">

        <form id="app-form" class="layui-form layuimini-form">

            <div class="layui-form-item">
                <label class="layui-form-label required">{{ea_trans('user profile picture')}}</label>
                <div class="layui-input-block layuimini-upload">
                    <input name="head_img" class="layui-input layui-col-xs6" lay-reqtext="{{ea_trans('please upload user profile picture')}}" placeholder="{{ea_trans('please upload user profile picture')}}" value="{{$row['head_img']}}">
                    <div class="layuimini-upload-btn">
                        <span><a class="layui-btn" data-upload="head_img" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg"><i class="fa fa-upload"></i> {{ea_trans('upload',false)}}</a></span>
                        <span><a class="layui-btn layui-btn-normal" id="select_head_img" data-upload-select="head_img" data-upload-number="one"><i class="fa fa-list"></i> {{ea_trans('select',false)}}</a></span>
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label required">{{ea_trans('login account')}}</label>
                <div class="layui-input-block">
                    <input type="text" name="username" class="layui-input" readonly value="{{$row['username']}}">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">{{ea_trans('user mobile phone')}}</label>
                <div class="layui-input-block">
                    <input type="text" name="phone" class="layui-input" lay-reqtext="{{ea_trans('please enter the user mobile phone number')}}" placeholder="{{ea_trans('please enter the user mobile phone number')}}" value="{{$row['phone']}}">
                    <tip>{{ea_trans('please enter the user mobile phone number')}}</tip>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">{{ea_trans('login method')}}</label>
                <div class="layui-input-block">
                    @foreach($notes['login_type'] as $key=>$value)
                        <input type="radio" name="login_type" lay-skin="primary" title="{{$value}}" value="{{$key}}" lay-filter="loginType-filter" @if($key==$row['login_type']) checked @endif>
                    @endforeach
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">{{ea_trans('memo')}}</label>
                <div class="layui-input-block">
                    <textarea name="remark" class="layui-textarea" placeholder="{{ea_trans('please enter the remarks information')}}">{{$row['remark']}}</textarea>
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
