@extends('layouts.app')
@section('title', '注册')
@section('css')
    <style type="text/css">
        .layui-form-checkbox {
            width: 75px;
        }
    </style>
@endsection

@section('content')
    <blockquote class="layui-elem-quote layui-quote-nm">
        <div class="layui-tab layui-tab-brief" lay-filter="user">
            <ul class="layui-tab-title">
                <li><a href="{{ route('login') }}">登录</a></li>
                <li class="layui-this">注册</li>
            </ul>
            <div class="layui-form layui-tab-content" style="padding: 20px 0;">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form layui-form-pane">
                        <form method="post" action="{{ route('register') }}">
                            <div class="layui-form-item">
                                <label for="email" class="layui-form-label">账号</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="email" name="email" lay-verify="email" autocomplete="off"
                                           class="layui-input" value="{{ old('email') }}" />
                                </div>
                                <div class="layui-form-mid layui-word-aux">email地址，将会成为您的登入名</div>
                            </div>
                            <div class="layui-form-item">
                                <label for="password" class="layui-form-label">密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" id="password" name="password" lay-verify="pass"
                                           autocomplete="off" class="layui-input"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="password-confirmation" class="layui-form-label">确认密码</label>
                                <div class="layui-input-inline">
                                    <input type="password" id="password-confirmation" name="password_confirmation"
                                           lay-verify="pass" autocomplete="off" class="layui-input"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="type" class="layui-form-label">用户类型</label>
                                <div class="layui-input-inline">
                                    <input type="radio" name="type" value="1" title="个人"
                                           lay-filter="user-type" {{ !old('type') || old('type') == 1 ? 'checked' : '' }}>
                                    <input type="radio" name="type" value="2" title="企业"
                                           lay-filter="user-type" {{ old('type') == 2 ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="layui-form-item" style="display: none;">
                                <label for="license" class="layui-form-label">营业执照</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="license" name="license" autocomplete="off"
                                           class="layui-input" value="{{ old('license') }}"/>
                                </div>
                                <div class="layui-form-mid layui-word-aux">请输入营业执照注册号</div>
                            </div>
                            <div class="layui-form-item" style="display: none;">
                                <label for="company" class="layui-form-label">企业名</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="company" name="company" autocomplete="off"
                                           class="layui-input" value="{{ old('company') }}"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="real_name" class="layui-form-label">真实姓名</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="real_name" name="real_name" lay-verify="required"
                                           autocomplete="off" class="layui-input" value="{{ old('real_name') }}"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="id_number" class="layui-form-label">身份证号</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="id_number" name="id_number" lay-verify="required|identity"
                                           autocomplete="off" class="layui-input" value="{{ old('id_number') }}"/>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label for="phone" class="layui-form-label">手机号码</label>
                                <div class="layui-input-inline">
                                    <input type="text" id="phone" name="phone" lay-verify="required|phone"
                                           autocomplete="off" class="layui-input" value="{{ old('phone') }}"/>
                                </div>
                            </div>
                            <div class="layui-form-item" style="margin-top:10px;">
                                {{ csrf_field() }}
                                <button class="layui-btn" lay-filter="*" lay-submit="">立即注册</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </blockquote>
@endsection

@section('js')
    <script type="text/javascript">
        layui.use(['layer', 'form'], function () {
            var layer = layui.layer;
            var form = layui.form;

            @if ($errors->any())
            layer.msg("{{ $errors->getBag('default')->first() }}", {icon: 5, anim: 6});
            @endif

            form.verify({pass: [/^[\S]{6,20}$/, '密码必须6到20位，且不能出现空格']});
            form.on('radio(user-type)', function (data) {
                if (data.value == 2) {
                    $('#license, #company').closest('.layui-form-item').show();
                } else {
                    $('#license, #company').closest('.layui-form-item').hide();
                }
            });

            @if (old('type') == 2)
            $('#license, #company').closest('.layui-form-item').show();
            @endif
        });
    </script>
@endsection
