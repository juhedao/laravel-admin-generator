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

                    Seeds根目录: <input type="text"  value="{{$filesRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" data-option-type="models-root" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾 修改根目录用于复制Seeds
                </div>
            </div>
        </div>
        <div class="col-md-10" style="padding-right: 0">
            <div class="panel panel-default">
                <div class="panel-heading">当前表: <a id="current-table" href="#"></a> </div>
                <form method="post"  action="create-model-ajax" id="create-view-form">
                <div class="panel-body form-inline">

                    <div class="des">
                        <span style="width: 140px">use namespace：</span><textarea  style="padding:10px" placeholder="* 一行一个，不带use;" id="model-namespace" name="model-namespace" rows="3"></textarea>
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">extends：</span><input style="width: 320px" type="text" id="model-extends" name="model-extends" value="Model" class="form-control">
                    </div>
                    <div class="des">
                        <span style="width: 140px">implements：</span><input placeholder="逗号分隔" style="width: 320px" type="text" id="model-implements" name="model-implements"  class="form-control">
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">特性Trait：</span><input placeholder="逗号分隔，结尾不带;" style="width:80%" type="text" id="model-trait" name="model-trait"  class="form-control">
                    </div>
                    <div class="des">
                        <span style="width: 140px">表名$table：</span><input placeholder="必须" style="width: 320px" type="text" id="model-table" name="model-table"  class="form-control">
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">主键$primaryKey：</span><input placeholder="留空默认为id" style="width: 320px" type="text" id="model-primaryKey" name="model-primaryKey"  class="form-control">
                    </div>
                    <div class="des">
                        <span style="width: 140px">时间戳$timestamps：</span>
                        <input type="checkbox" checked name="model-timestamps" id="model-timestamps">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;自定义时间戳格式$dateFormat: <input type="text" placeholder="可为空" style="width:320px" id="model-dateFormat" name="model-dateFormat" class="form-control">
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">软删除$timestamps：</span>
                        <input type="checkbox"  name="model-SoftDeletes" id="model-SoftDeletes">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$dates: <input type="text" placeholder="可为空" style="width:320px" value="deleted_at" id="model-dates" name="model-dates" class="form-control">
                    </div>
                    <div class="des">
                        <span style="width: 140px">事件：</span>
                        <input type="checkbox" value="creating"  name="model-event[]"> creating
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="created"  name="model-event[]"> created
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="updating"  name="model-event[]"> updating
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="updated"  name="model-event[]"> updated
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="saving"  name="model-event[]"> saving
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="saved"  name="model-event[]"> saved
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="deleting"  name="model-event[]"> deleting
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="deleted" name="model-event[]"> deleted
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="restoring"  name="model-event[]"> restoring
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" value="deleted"  name="model-event[]"> deleted
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">$fillable：</span><div style="display: inline-block;width:80%" id="model-fillable-box"><input placeholder="逗号分隔，结尾不带;" style="width:100%" type="text" id="model-fillable" name="model-fillable"  class="form-control"></div>
                    </div>
                    <div class="des">
                        <span style="width: 140px">$hidden：</span><div style="display: inline-block;width:80%" id="model-hidden-box"><input placeholder="逗号分隔，结尾不带;" style="width:100%" type="text" id="model-hidden" name="model-hidden"  class="form-control"></div>
                    </div>
                    <div class="des sp">
                        <span style="width: 140px">$guarded：</span><div style="display: inline-block;width:80%" id="model-guarded-box"><input placeholder="逗号分隔，结尾不带;" style="width:100%" type="text" id="model-guarded" name="model-guarded"  class="form-control"></div>
                    </div>
                    <div class="des">
                        <span style="width: 140px">scope：</span><div style="display: inline-block;width:80%">
                            <textarea  style="padding:10px;width:100%" placeholder=" 一行一个" id="model-scope-more" name="model-scope-more" rows="3"></textarea>
                            <div id="model-scope-box" name="model-scope-box" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="des sp" style="border-top:#0000C2 solid 5px;font-weight: 600;">
                        <input id="isArdent" name="isArdent" type="checkbox"> 是否使用Ardent
                    </div>
                    <style type="text/css">
                        .ardent{display: none}
                    </style>
                    <div class="des sp ardent">               
                        <span style="width: 140px">属性：</span><div style="display: inline-block;width:80%">
                            <input type="checkbox" value="autoHydrateEntityFromInput" checked  name="model-feature[]"> 插入自动完成
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="forceEntityHydrationFromInput" checked  name="model-feature[]"> 更新自动完成
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="autoPurgeRedundantAttributes" checked name="model-feature[]"> 清除多余表单数据
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="autoHashPasswordAttributes" checked  name="model-feature[]"> 自动转换安全文本
                        </div>
                    </div>
                    <div class="des ardent">
                        <span style="width: 140px">多余表单字段：</span><div style="display: inline-block;width:80%"  id="model-purge-box"><input  placeholder="逗号分隔，结尾不带;" style="width:100%" type="text" id="model-purge" name="model-purge"  class="form-control"></div>
                    </div>
                    <div class="des sp ardent">
                        <span style="width: 140px">转换安全字段：</span><div style="display: inline-block;width:80%"  id="model-hash-box"><input placeholder="逗号分隔，结尾不带;" style="width:100%" type="text" id="model-hash" name="model-hash"  class="form-control"></div>
                    </div>

                    <div class="des sp ardent" >
                        <span style="width: 140px">Hooks：</span><div style="display: inline-block;width:80%">
                            <input type="checkbox" value="beforeSave" checked name="model-hooks[]"> beforeSave()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="afterSave" checked name="model-hooks[]"> afterSave()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="beforeValidate" checked name="model-hooks[]"> beforeValidate()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="afterValidate" checked  name="model-hooks[]"> afterValidate()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="beforeCreate"  name="model-hooks[]"> beforeCreate()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="afterCreate"  name="model-hooks[]"> afterCreate()
                            &nbsp;&nbsp;&nbsp;

                            <input type="checkbox" value="beforeUpdate"  name="model-hooks[]"> beforeUpdate()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="afterUpdate"  name="model-hooks[]"> afterUpdate()
                            &nbsp;&nbsp;&nbsp;
                            <input type="checkbox" value="beforeDelete"  name="model-hooks[]"> beforeDelete()

                        </div>
                    </div>

                    <br>
                    <button class="btn btn-success" id="create-model">创建此Model</button>
                    <br>
                    备注: 点右边的数据库表名!
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-blue">
                <div class="panel-heading dark-overlay"><span class="glyphicon glyphicon-check"></span>从数据库</div>
                <div class="panel-body">
                    <ul id="tables-list" class="todo-list" style="padding: 20px 0">

                    </ul>
                </div>

            </div>
        </div>
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

            ajax.getTables('default',function(data){
                var list = [];
                for(var i=0;i<data.length;i++){
                    var value = data[i]['TABLE_NAME'];
                    list.push('<li data-table-name="'+value+'" class="todo-list-item">&nbsp;&nbsp;'+(i+1)+' : <a href="javascript:void(0);">'+value+'</a></li>');
                }
                $('#tables-list').html(list.join(''));
            });

            $(document).on('click','#tables-list .todo-list-item',function(){
                var tableName = $(this).data('table-name');
                $('#model-table').val(tableName);

                $('#current-table').html(tableName);

                ajax.getColumns('default',tableName,function(data){
                    var fieldCheckbox = [];

                    for(var i=0;i<data.length;i++){
                        var item = data[i];
                        fieldCheckbox.push('<input class="form-control" type="checkbox" name value="'+item.Field+'"> '+item.Field);
                        if(item.field=='deleted_at'){
                            $('#model-SoftDeletes').prop('checked',true);
                            $('#model-dates').val('deleted_at');
                        }
                    }
                    var list = fieldCheckbox.join('&nbsp;&nbsp;&nbsp;');
                    $('#model-fillable-box').html(list.replace(/name/g,"name='model-fillable[]'"));
                    $('#model-hidden-box').html(list.replace(/name/g,"name='model-hidden[]'"));
                    $('#model-guarded-box').html(list.replace(/name/g,"name='model-guarded[]'"));
                    $('#model-scope-box').html(list.replace(/name/g,"name='model-scope[]'"));
                    $('#model-purge-box').html(list.replace(/name/g,"name='model-purge[]'"));
                    $('#model-hash-box').html(list.replace(/name/g,"name='model-hash[]'"));
                });

            });

            $('#isArdent').click(function(){
                if(this.checked){
                    $('#model-extends').val('\\LaravelArdent\\Ardent\\Ardent');
                    $('.ardent').show();
                }else{
                    $('#model-extends').val('Model');
                    $('.ardent').hide();
                }

            });

            $('#open-validate-box').click(function(){
                var status = $(this).data('status');
                if(status=='open'){
                    $(this).data('status',close);
                    $('#validate-box').show();
                    $(this).html('<i class="glyphicon glyphicon-arrow-down"></i> 折叠验证规则和信息');
                }else{
                    $(this).data('status','open');
                    $('#validate-box').hide();
                    $(this).html('<i class="glyphicon glyphicon-arrow-up"></i> 展开验证规则和信息');
                }
            });

            $('#create-model').click(function(){
                var modelExtends = $('#model-extends');
                if($.trim(modelExtends.val())==''){
                    msg.warning('请填写extends');
                    modelExtends.focus();
                    return false;
                }
                var modelTable = $('#model-table');
                if($.trim(modelTable.val())==''){
                    msg.warning('请填写$table名称');
                    modelTable.focus();
                    return false;
                }

                $.ajax({
                    'type' : 'POST',
                    'url'  : '/admin/generator/create-model-ajax',
                    'data' : $('#create-view-form').serialize(),
                    'dataType' : 'json'
                }).done(function(data){
                    msg.success(data.msg);
                }).fail(function(){
                    msg.warning(data.msg);
                });
                return false;
            });

        })(window.jQuery);
    </script>
@stop