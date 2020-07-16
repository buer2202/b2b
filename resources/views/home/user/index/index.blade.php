@extends('home.layouts.base')

@section('content')
<h3>您好！{{ auth()->user()->real_name }}（ID: {{ auth()->id() }}）</h3>
<blockquote class="layui-elem-quote" style="margin-top: 20px;">
    <h3>您的账户可用余额：{{ (float)auth()->user()->userAsset->balance }}元，已冻结金额：{{ (float)auth()->user()->userAsset->frozen }}元。</h3>
</blockquote>
@endsection
