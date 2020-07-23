@extends('admin.layouts.base')
@section('title', '修改密码')

@section('content')
<div class="panel panel-default" style="width:600px">
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">原密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="origin_password" />
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" />
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">确认密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password_confirmation" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" id="submit" class="btn btn-primary">修改</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $('#submit').click(function () {
        buer_post("{{ route('admin.password.update') }}", {
            origin_password: $('[name="origin_password"]').val(),
            password: $('[name="password"]').val(),
            password_confirmation: $('[name="password_confirmation"]').val()
        });
    });
</script>
@endsection
