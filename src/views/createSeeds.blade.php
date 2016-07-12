@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
@stop

@section('Page')
    <div class="row" style="min-width: 1500px">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body form-inline">

                    Seeds根目录: <input type="text"  value="{{$filesRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" data-option-type="seeds-root" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾 修改根目录用于复制Seeds
                </div>
            </div>
        </div>
        <div class="col-md-10" style="padding-right: 0">
            <div class="panel panel-default">
                <div class="panel-body form-inline">
                    <input type="hidden" id="table-name" value="">
                    <table id="seeds-list" class="table table-bordered table-hover" style="margin-top: 15px; border-radius: 3px">
                        <thead>
                        <th style="width:20px">#</th>
                        <th>字段名</th>
                        <th>值</th>

                        <th>数据类型</th>
                        <th>数据长度</th>
                        <th>注释</th>

                        </thead>
                    </table>
                    <br>
                    <button class="btn btn-success" id="create">创建此Seeds</button>
                    <br>
                    备注: 点右边的数据库表名，可点多次！
                </div>
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
                        msg.warning('seeds根目录路径必须以/并以/结尾');
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

            function  dbToItem(data){
                var list = '<tbody class="seeds-item">';
                list += '<tr><td colspan="7"></td></tr>';
                list += '<tr>';
                list += '<td colspan="4" style="background: #f5f5f5">重复 : <input class="form-control repeat" type="text" value="1" style="width:80px"> </td>';
                list += '<td colspan="3" style="background: #f5f5f5;text-align: right"><button class="btn btn-default del">删除</button> </td>';
                list += '</tr>';
                for(var i=0;i<data.length;i++){

                        var values = data[i];
                        var cname = values.Field;
                        var para = values.Type.split("(");
                        var type = para[0];
                        var clength = '';
                        if(para.length>1){
                            para = para[1].split(")");
                            clength =para[0];
                        }
                        var cdefault = values.Default?values.Default:'';
                        var cnullable = values.Null=='NO';
                        var cunique = values.Key=='UNI';
                        var cunsigned = /unsigned/.test(values.Type);
                        var ccomment=values.Comment;
                        var ckey = values.Key;
                        if(ckey!='PRI'){
                            list += '<tr>';
                            list += '<td>'+(i+1)+'</td>';
                            list += '<td style="font-size: 22px">'+cname+'</td>';
                            list += '<td><textarea class="form-control field-value" style="width:100%" data-must="'+cnullable+'" data-indexs="'+(i+1)+'" data-field="'+cname+'" value="'+cdefault+'" type="text"></textarea> </td>';
                            //list += '<td><select class="str-template form-control"><option value="">请选择</option> </select> </td>';
                            list += '<td>'+type+'</td>';
                            list += '<td>'+clength+'</td>';
                            list += '<td>'+ccomment+'</td>';
                            list += '</tr>';
                        }


                }
                list += '<tr><td colspan="7"></td></tr>';
                list += '</tbody>';
                $('#seeds-list').append(list);
            }

            $(document).on('click','#tables-list .todo-list-item',function(){
                var tableName = $(this).data('table-name');
                if($('#seeds-list').find('.seeds-item').length==0){
                    $('#table-name').val(tableName);
                }
                if($.trim($('#table-name').val())==''||$.trim($('#table-name').val())==tableName){
                    ajax.getColumns('default',tableName,function(data){
                        dbToItem(data);
                    });
                }
            });

            $(document).on('click','#seeds-list .del',function(){
                $(this).parents('tbody').remove();
            });

            $('#create').click(function(){
                var items = $('#seeds-list .seeds-item');
                if(items.length>0){
                    var list = {'tableName':$('#table-name').val(),items:[]};
                    for(var i=0;i<items.length;i++){
                        var it = {'repeat':$(items[i]).find('.repeat').val(),items:{}};
                        var tr = $(items[i]).find('textarea');
                        for(var a=0;a<tr.length;a++){
                            var must = $(tr[a]).data('must');
                            var field = $(tr[a]).data('field');
                            var indexs = $(tr[a]).data('indexs');
                            var values = $.trim($(tr[a]).val());
                            if(must&&values==''&&field!='created_at'&&field!='updated_at'&&field!='soft_delete'&&field!='remember_token'){
                                msg.warning('第'+(i+1)+'个seeds的第'+indexs+'字段('+field+')为非空字段，必须有值！');
                                return false;
                            }
                            if(values!=''){
                                /*var tp={};
                                tp[field] = values;*/
                                it.items[field] = values;
                            }
                        }
                        list.items.push(it);
                    }
                    //alert(JSON.stringify(list));
                    var seedsData = {'seeds-data':list};
                    $.ajax({
                        'url' : '/admin/generator/save-seeds',
                        'type' : 'POST',
                        'data' : seedsData,
                        'dataType' : 'json'
                    }).done(function(data){
                        //document.write(JSON.stringify(data));
                        if(data.done){
                            msg.success(data.msg);
                        }else{
                            msg.warning(data.msg);
                        }

                    }).fail(function(data){
                        //document.write(JSON.stringify(data));
                        msg.warning('创建Seeds失败！');
                    });
                }else{
                    msg.warning('没有填充数据！');
                }
            });

        })(window.jQuery);
    </script>
@stop