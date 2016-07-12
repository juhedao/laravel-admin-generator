@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
@stop

@section('HeaderScript')
@stop

@section('Page')
<div class="row" style="min-width: 1500px">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body form-inline">

                主routes文件: <input type="text"  value="{{$filesRoot}}" class="form-control" style="width: 320px">
                &nbsp;&nbsp;
                <button type="button" data-option-type="routes-main" class="btn btn-default set-option">修改</button>
                &nbsp;&nbsp;

            </div>
        </div>
    </div>
    <div class="col-md-10" style="padding-right: 0">

</div>

@stop

@section('FooterScript')
<script src="/assets/juhedao/admin-generator/js/bootstrap-datepicker.js"></script>
<script src="/assets/juhedao/admin-generator/js/bootstrap-table.js"></script>
<script src="/assets/juhedao/admin-generator/js/bootstrap-table-zh-CN.min.js"></script>
<script type="text/javascript">
    (function ($) {
        //设置根目录
        $('.set-option').click(function(){
            var option = $(this).parent().find("input[type='text']");
            var path = option.val();
            var obj=$(this);

            var optionType = $(this).data('option-type');
            if(path==''){
                msg.warning('请输入要修改的值');
                return false;
            }
            if(optionType == 'migrations-root'){
                if(!/^\//.test(path)||!/\/$/.test(path)){
                    msg.warning('models根目录路径必须以/并以/结尾');
                    return false;
                }
            }else{
                path = path.replace(/[\r\n]/g,'');
            }
            ajax.setConfig(optionType,path,function(){
                getMigrationTemplateTables();
            });
        });



    })(window.jQuery);
</script>
@stop