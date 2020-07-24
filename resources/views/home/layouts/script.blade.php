<script src="/js/jquery-1.11.0.min.js"></script>
<script src="/layui/layui.js?v=2.4.5"></script>
<script src="/js/buer_post.js"></script>
<script>
$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
layui.use(['element', 'layer', 'form'], function () {
    var layer = layui.layer;

    // 闪存弹框
    @if (session()->has('alert'))
        layer.alert("{{ session('alert') }}");
    @endif
});
</script>
