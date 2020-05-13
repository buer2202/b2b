@extends('admin.layouts.base')

@section('title', '欢迎')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">管理系统</div>

                <div class="panel-body">
                    欢迎来到{{ config('app.name', 'Laravel') }}！
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
