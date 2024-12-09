@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form" autocomplete="off">

        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('user profile picture',true,'common')}}</label>
            <div class="layui-input-block layuimini-upload">
                <input name="head_img" class="layui-input layui-col-xs6" lay-verify="required" placeholder="{{ea_trans('Please Enter',false)}}" value="">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="head_img" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> {{ea_trans('upload',false)}}</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_head_img" data-upload-select="head_img" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> {{ea_trans('select',false)}}</a></span>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('login account',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" name="username" class="layui-input" lay-verify="required" placeholder="{{ea_trans('Please Enter',false)}}" value="" readonly onclick="this.removeAttribute('readonly');">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label ">{{ea_trans('login password',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input" placeholder="{{ea_trans('Please Enter',false)}}" value="" readonly onclick="this.removeAttribute('readonly');">
                <tip>{{ea_trans('default for empty',true,'common')}} 123456</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('user mobile phone',true,'common')}}</label>
            <div class="layui-input-block">
                <input type="text" name="phone" class="layui-input" placeholder="{{ea_trans('Please Enter',false)}}" value="">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">{{ea_trans('role permission',true,'common')}}</label>
            <div class="layui-input-block">
                @foreach($auth_list as $key=>$val)
                    <input type="checkbox" name="auth_ids[{{$key}}]" lay-skin="primary" title="{{$val}}">
                @endforeach
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
