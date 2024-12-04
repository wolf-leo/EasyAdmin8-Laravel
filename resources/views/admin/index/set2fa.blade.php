@include('admin.layout.head')
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form" autocomplete="off">
        @if($old_secret)
            <div class="layui-card">
                <div class="layui-card-header">{{ea_trans('prompt',false)}}</div>
                <div class="layui-card-body">
                    {{ea_trans('reminder information')}}
                </div>
            </div>
        @endif
        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('verify key')}}</label>
            <div class="layui-input-block">
                <input type="text" name="ga_secret" class="layui-input" value="{{$secret}}" readonly disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('qrcode')}}</label>
            <div class="layui-input-block">
                <img src="{{$dataUri}}" alt="{{ea_trans('qrcode')}}" style="width: 200px;height: 200px">
                <div class="layui-text layui-font-cyan layui-font-12">
                    {{ea_trans('use')}}&nbsp;
                    <a href="https://2fas.com" target="_blank"><span class="layui-text layui-font-blue">2FAS</span></a>
                    &nbsp;{{ea_trans('or')}}&nbsp;&nbsp;
                    <a href="https://cn.bing.com/search?q=Google+Authenticator" target="_blank"><span class="layui-text layui-font-blue">Google Authenticator</span></a>
                    &nbsp;{{ea_trans('reminder information2')}}
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label required">{{ea_trans('ga_code')}}</label>
            <div class="layui-input-block">
                <input type="text" name="ga_code" class="layui-input" maxlength="6" lay-verify="required" placeholder="{{ea_trans('scan and enter the code')}}" value="">
            </div>
        </div>
        <div class=" hr-line">
        </div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>{{ea_trans('confirm',false)}}</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">{{ea_trans('reset',false)}}</button>
        </div>

    </form>
</div>
@include('admin.layout.foot')
