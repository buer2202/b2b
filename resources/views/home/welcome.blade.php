@extends('layouts.app')
@section('title', '首页')

@section('content')
<blockquote class="layui-elem-quote layui-quote-nm">
    欢迎来到{{ config('app.name') }}！
    <a class="layui-icon" href="{{ route('login') }}" style="font-size: 14px">&#xe65b; 开始使用 &#xe65b;</a>
</blockquote>
@endsection
