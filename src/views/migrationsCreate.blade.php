@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
    <script type="text/javascript">
        function cup(obj){
            var p = $(obj).parents('tr');
            var prev = p.prev();
            if(typeof prev.html()=='undefined'){
                alert('这已经在最顶部了！');
            }else{
                prev_html=prev.html();
                p_html= p.html();
                p.html(prev_html);
                prev.html(p_html);
            }
        }
        function cdown(obj){
            var p = $(obj).parents('tr');
            var prev = p.next();
            if(typeof prev.html()=='undefined'){
                alert('这已经在最底部了！');
            }else{
                prev_html=prev.html();
                p_html= p.html();
                p.html(prev_html);
                prev.html(p_html);
            }
        }
    </script>



@stop

@section('Page')
    <div class="row" style="min-width: 1500px">

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body form-inline">

                    migrations根目录: <input type="text"  value="{{$filesRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" data-option-type="migrations-root" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾 修改根目录用于复制migration
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body form-inline">
                    模板: <select class="form-control" id="connection-template">
                        <option value="{{$migrationsConnection}}">当前，或选择模板</option>

                        <option value="driver=mysql;host=localhost;database=;username=;password=;charset=utf8;collation=utf8_unicode_ci;prefix=;strict=false;">mysql</option>

                    </select> &nbsp;&nbsp;
                    <button type="button" data-option-type="migrations-connection" class="btn btn-default set-option">连接此数据库</button>
                    &nbsp;&nbsp;
                    *别填错哦，这里可没有格式验证哦
                    <br>
                    配置: <textarea style="width: 80%;margin-top: 10px" id="connection-option" class="form-control" rows="5">{{str_replace(';',';'.PHP_EOL,$migrationsConnection)}}</textarea>

                </div>
            </div>
        </div>

        <div class="col-md-10" style="padding-right: 0">
            <div class="panel panel-default">
                <div class="panel-body form-inline">
                    <style type="text/css">
                        .kuai a{
                            display: block;
                            background: #f5f5f5;
                            padding: 0px 10px;
                            height: 30px;
                            line-height: 30px;
                            float: left;
                            border-radius: 3px;
                            margin-right: 10px;
                            margin-top: 5px;
                        }
                        .kuai td{
                            vertical-align: middle;
                        }
                    </style>
                    <div class="kuai">
                        <a style="background: none;color: #666;font-weight: 600">快捷字段</a>

                        <a data-type="1" href="javascript:void(0);">increments('id')</a>
                        <a data-type="2" href="javascript:void(0);">bigIncrements('id')</a>

                        <a data-type="3" href="javascript:void(0);" style="color:#FF3300">integer</a>
                        <a data-type="4" href="javascript:void(0);" style="color:#FF3300">bigInteger</a>
                        <a data-type="5" href="javascript:void(0);" style="color:#FF3300">mediumInteger</a>
                        <a data-type="6" href="javascript:void(0);" style="color:#FF3300">tinyInteger</a>
                        <a data-type="7" href="javascript:void(0);" style="color:#FF3300">smallInteger</a>

                        <a data-type="8" href="javascript:void(0);" style="color:#006">string(35)</a>
                        <a data-type="9" href="javascript:void(0);" style="color:#006">string(70)</a>
                        <a data-type="10" href="javascript:void(0);" style="color:#006">string(120)</a>
                        <a data-type="11" href="javascript:void(0);" style="color:#006">string(255)</a>
                        <a data-type="12" href="javascript:void(0);" style="color:#006">char</a>
                        <a data-type="13" href="javascript:void(0);" style="color:#006">text</a>
                        <a data-type="14" href="javascript:void(0);" style="color:#006">mediumText</a>
                        <a data-type="15" href="javascript:void(0);" style="color:#006">longText</a>

                        <a data-type="16" href="javascript:void(0);" style="color:#0c9">decimal</a>
                        <a data-type="17" href="javascript:void(0);" style="color:#0c9">float</a>
                        <a data-type="18" href="javascript:void(0);" style="color:#0c9">double</a>
                        <a data-type="19" href="javascript:void(0);" style="color:#0c9">boolean</a>

                        <a data-type="20" href="javascript:void(0);" style="color:#c3f">time</a>
                        <a data-type="21" href="javascript:void(0);" style="color:#c3f">timestamp</a>
                        <a data-type="22" href="javascript:void(0);" style="color:#c3f">date</a>
                        <a data-type="23" href="javascript:void(0);" style="color:#c3f">dateTime</a>

                        <a data-type="24" href="javascript:void(0);" style="color:#f90">morphs</a>
                        <a data-type="25" href="javascript:void(0);" style="color:#f90">enum</a>
                        <a data-type="26" href="javascript:void(0);" style="color:#f90">binary</a>
                        <a data-type="27" href="javascript:void(0);" style="color:#f90">json</a>
                        <a data-type="28" href="javascript:void(0);" style="color:#f90">jsonb</a>
                        <a data-type="29" href="javascript:void(0);" style="color:#f90">uuid</a>

                        <div class="clearfix"></div>
                    </div>
                    <table class="table table-bordered table-hover" style="margin-top: 15px; border-radius: 3px">
                        <thead>
                            <tr>
                                <th style="width:20px">#</th>
                                <th style="width: 25px"><input id="check-all" checked type="checkbox"> </th>
                                <th>字段名</th>
                                <th>数据类型</th>
                                <th>数据长度</th>
                                <th>默认值</th>
                                <th style="width:60px;">非空</th>
                                <th style="width:60px">唯一</th>
                                <th style="width:60px">
                                    符号</th>
                                <th>备注</th>

                                <th style="width: 90px">排列/操作</th>
                            </tr>
                        </thead>
                        <tbody id="clist">

                        </tbody>
                    </table>
<div style="font-weight: 600">
                    <input id="isRememberToken" type="checkbox"> rememberToken
                    <br>
                    <input id="isSoftDeletes" type="checkbox"> softDeletes
                    <br>
    <input name="timestampsType" value="0" type="radio"> 无 &nbsp;&nbsp;&nbsp;&nbsp;<input name="timestampsType" value="1" type="radio" checked> timestamps &nbsp;&nbsp;&nbsp;&nbsp;<input name="timestampsType" value="2" type="radio"> nullableTimestamps
</div>
                    <button id="delete-all" class="btn btn-default">清空字段</button><br>
                    表名:<input class="form-control translation" id="table-name" type="text" style="width: 220px" >
                    &nbsp;&nbsp;表注释：<input class="form-control" id="table-comment" type="text" style="width: 220px" >
                    &nbsp;&nbsp;&nbsp;&nbsp;<button id="create" class="btn btn-success">创建Migrations</button>
                    <br>
                    备注: 点右边的数据库表名，可点多个，不会添加相同的字段名！
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="panel panel-blue">
                <div class="panel-body tabs">

                    <div class="panel-heading dark-overlay"><span class="glyphicon glyphicon-check"></span>从数据库</div>
                    <div class="panel-body">
                        <ul id="migration-template-tables" class="todo-list" style="padding: 20px 0">

                        </ul>
                    </div>

                </div>
            </div><!--/.panel-->
        </div>
    </div>

<select id="cctype" style="display: none">
    <option value='string'>string</option>
    <option value='text'>text</option>
    <option value='mediumText'>mediumText</option>
    <option value='longText'>longText</option>
    <option value='decimal'>decimal</option>
    <option value='float'>float</option>
    <option value='double'>double</option>
    <option value='boolean'>boolean</option>
    <option value="increments">increments</option>
    <option value="bigIncrements">bigIncrements</option>
    <option value='integer'>integer</option>
    <option value='bigInteger'>bigInteger</option>
    <option value='mediumInteger'>mediumInteger</option>
    <option value='tinyInteger'>tinyInteger</option>
    <option value='smallInteger'>smallInteger</option>
    <option value='char'>char</option>
    <option value='dateTime'>dateTime</option>
    <option value='time'>time</option>
    <option value='timestamp'>timestamp</option>
    <option value='date'>date</option>
    <option value='enum'>enum</option>
    <option value='binary'>binary</option>
    <option value='json'>json</option>
    <option value='jsonb'>jsonb</option>
    <option value='uuid'>uuid</option>
</select>
@stop

@section('FooterScript')
    <script src="/assets/juhedao/admin-generator/js/bootstrap-datepicker.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table-zh-CN.min.js"></script>
    <script type="text/javascript">
        (function ($) {
            function getMigrationTemplateTables(){
                ajax.getTables('migrations-connection',function(data){
                    var list = [];
                    for(var i=0;i<data.length;i++){
                        var value = data[i]['TABLE_NAME'];
                        list.push('<li class="todo-list-item">&nbsp;&nbsp;'+(i+1)+' : <a href="javascript:void(0);">'+value+'</a></li>');
                    }
                    $('#migration-template-tables').html(list.join(''));
                });
            }
            getMigrationTemplateTables();



            $('#delete-all').click(function(){
                $('#clist').html('');
            });

            //设置根目录
            $('.set-option').click(function(){
                var option = $(this).parent().find("input[type='text']");
                var path = option.val();
                var obj=$(this);
                if(typeof path=='undefined'){
                    path=$('#connection-option').val();
                }
                var optionType = $(this).data('option-type');
                if(path==''){
                    msg.warning('请输入要修改的值');
                    return false;
                }
                if(optionType == 'migrations-root'){
                    if(!/^\//.test(path)||!/\/$/.test(path)){
                        msg.warning('migration根目录路径必须以/并以/结尾');
                        return false;
                    }
                }else{
                    path = path.replace(/[\r\n]/g,'');
                }
                ajax.setConfig(optionType,path,function(){
                    getMigrationTemplateTables();
                });
            });
            $('#connection-template').change(function(){
                $('#connection-option').val($(this).val().replace(/\;/g,';\r\n'));
            });

            function addColumn(cname,ctype,clength,cdefault,cnullable,cunique,cunsigned,ccomment){

                var cn=$("#clist input[name='cname']");
                var iscn=false;
                if(cname!=''){
                for(var i=0;i<cn.length;i++){
                    if($(cn[i]).val()== $.trim(cname)){
                        iscn=true;
                    }
                }}
                if(!iscn) {
                    var clist = $('#clist');
                    var len = clist.find('tr').length;

                    var list = '<tr>';
                    list += '<td class="numr">' + (len + 1) + '</td>';
                    list += '<td><input class="form-control sele" type="checkbox" checked></td>';
                    list += '<td><input  list="name' + (len + 1) + '_list"  class="form-control translation" type="text" name="cname" value="' + cname + '" style="width:160px"><datalist id="name' + (len + 1) + '_list" style="display: none"></datalist></td>';
                    list += '<td><select class="form-control ctype" name="ctype" ><option value="' + ctype + '">' + ctype + '</option> </select></td>';
                    list += '<td><input  class="form-control" type="text" name="clength" value="' + clength + '"  style="width:120px"> </td>';
                    list += '<td><input  class="form-control" type="text" name="cdefault" value="' + cdefault + '"  style="width:160px"> </td>';
                    if (ctype != 'increments' && ctype != 'bigIncrements') {
                        list += '<td>n <input class="form-control" type="checkbox" name="cnullable" ' + (cnullable ? 'checked' : '') + '> </td>';
                        list += '<td>u <input class="form-control" type="checkbox" name="cunique" ' + (cunique ? 'checked' : '') + '> </td>';
                    } else {
                        list += '<td>n <input class="form-control" type="checkbox" name="cnullable" disabled> </td>';
                        list += '<td>u <input class="form-control" type="checkbox" name="cunique" disabled> </td>';
                    }

                    if (ctype == 'bigInteger' || ctype == 'integer' || ctype == 'mediumInteger' || ctype == 'smallInteger' || ctype == 'tinyInteger') {
                        list += '<td>s <input class="form-control" type="checkbox" name="cunsigned" ' + (cunsigned ? 'checked' : '') + '> </td>';
                    } else {
                        list += '<td>s <input class="form-control" type="checkbox" name="cunsigned" disabled></td>';
                    }
                    list += '<td><input  class="form-control comment" type="text" name="ccomment" value="' + ccomment + '"  style="width:160px"> </td>';
                    list += '<td>';
                    list += '<i title="上移" class="glyphicon glyphicon-arrow-up up" onclick="cup(this);return false;" style="color:#0c9;cursor: pointer;padding-top: 10px"></i>';
                    list += '&nbsp;&nbsp;<i title="下移" class="glyphicon glyphicon-arrow-down down" onclick="cdown(this);return false;"  style="color:#f90;cursor: pointer"></i>';
                    list += '&nbsp;&nbsp;<i title="删除" class="glyphicon glyphicon-remove del"   style="cursor: pointer"></i>';
                    list += '</td></tr>';
                    clist.append(list);
                }
            }

            $('.kuai a').click(function(){
                var ctype = $(this).data('type');
                switch(ctype){
                    case 1:
                        addColumn('id','increments','','',false,false,false,'主键ID');
                        break;
                    case 2:
                        addColumn('id','bigIncrements','','',false,false,false,'主键ID');
                        break;
                    case 3:
                        addColumn('','integer','','',false,false,false,'');
                        break;
                    case 4:
                        addColumn('','bigInteger','','',false,false,false,'');
                        break;
                    case 5:
                        addColumn('','mediumInteger','','',false,false,false,'');
                        break;
                    case 6:
                        addColumn('','tinyInteger','','',false,false,false,'');
                        break;
                    case 7:
                        addColumn('','smallInteger','','',false,false,false,'');
                        break;
                    case 8:
                        addColumn('','string','35','',false,false,false,'');
                        break;
                    case 9:
                        addColumn('','string','70','',false,false,false,'');
                        break;
                    case 10:
                        addColumn('','string','120','',false,false,false,'');
                        break;
                    case 11:
                        addColumn('','string','255','',false,false,false,'');
                        break;
                    case 12:
                        addColumn('','char','35','',false,false,false,'');
                        break;
                    case 13:
                        addColumn('','text','','',false,false,false,'');
                        break;
                    case 14:
                        addColumn('','mediumText','','',false,false,false,'');
                        break;
                    case 15:
                        addColumn('','longText','','',false,false,false,'');
                        break;
                    case 16:
                        addColumn('','decimal','16,2','',false,false,false,'');
                        break;
                    case 17:
                        addColumn('','float','5,2','',false,false,false,'');
                        break;
                    case 18:
                        addColumn('','double','12,2','',false,false,false,'');
                        break;
                    case 19:
                        addColumn('','boolean','','false',false,false,false,'');
                        break;
                    case 20:
                        addColumn('','time','','',false,false,false,'');
                        break;
                    case 21:
                        addColumn('','timestamp','','',false,false,false,'');
                        break;
                    case 22:
                        addColumn('','date','','',false,false,false,'');
                        break;
                    case 23:
                        addColumn('','dateTime','','',false,false,false,'');
                        break;
                    case 24:
                        addColumn('','morphs','','',false,false,false,'');
                        break;
                    case 25:
                        addColumn('','enum','','[]',false,false,false,'');
                        break;
                    case 26:
                        addColumn('','binary','','',false,false,false,'');
                        break;
                    case 27:
                        addColumn('','json','','',false,false,false,'');
                        break;
                    case 28:
                        addColumn('','jsonb','','',false,false,false,'');
                        break;
                    case 29:
                        addColumn('id','uuid','','',false,false,false,'');
                        break;
                }


            });

            $('#check-all').click(function(){
                $('#clist .sele').prop('checked',this.checked?'checked':'');
            });

            $(document).on('click','#clist .del',function(){
                $(this).parents('tr').remove();
            });

            $(document).on('mousedown','#clist .ctype',function(){
                var sel = $(this).val();
                if($(this).find('option').length<5){
                    if($(this).html($('#cctype').html())){
                        $(this).val(sel);
                    }
                }
            });

            $('#create').click(function(){
                var sele=$("#clist .sele:checked");
                var list=[];
                for(var i=0;i<sele.length;i++){
                    var se=sele[i];
                    var p=$(se).parents('tr');
                    var item={};
                    item.cname= $.trim(p.find("input[name='cname']").val());
                    if(item.cname==''){
                        msg.warning('请填第'+ p.find('.numr').text()+'行的字段名！');
                        p.find("input[name='cname']").focus();
                        return false;
                    }

                    item.ctype= $.trim(p.find("select[name='ctype']").val());
                    item.clength= $.trim(p.find("input[name='clength']").val());
                    item.cdefault= $.trim(p.find("input[name='cdefault']").val());
                    item.cnullable= p.find("input[name='cnullable']").prop('checked');
                    item.cunique= p.find("input[name='cunique']").prop('checked');
                    item.cunsigned= p.find("input[name='cunsigned']").prop('checked');
                    item.ccomment= $.trim(p.find("input[name='ccomment']").val());
                    list.push(item);
                }
                if($.trim($('#table-name').val())==''){
                    msg.warning('请填写表名！');
                    $('#table-name').focus();
                    return false;
                }
                var postData = {'columns':list,
                    'table-name':$.trim($('#table-name').val()),
                    'table-comment':$.trim($('#table-comment').val()),
                    'isRememberToken':$('#isRememberToken').prop('checked'),
                    'isSoftDeletes':$('#isSoftDeletes').prop('checked'),
                    'timestampsType':$.trim($('input[name="timestampsType"]:checked').val())
                };
                //alert(JSON.stringify(postData));
                $.ajax({
                    'url': '/admin/generator/save-migrations',
                    'type': 'POST',
                    'dataType': 'text',
                    'data': postData
                }).done(function (data) {
                    msg.success('创建migrations成功！');
                }).fail(function(data){
                    document.write(JSON.stringify(data));
                    msg.warning('创建migrations失败！');
                });

            });

            function dbToItem(values){

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

                var ctype = 'string';
                switch(type.toLowerCase()){
                    case 'int':
                        ctype='integer';
                        break;
                    case 'bigint':
                        ctype='bigInteger';
                        break;
                    case 'mediumint':
                        ctype='mediumInteger';
                        break;
                    case 'tinyint':
                        if (values.Type == 'tinyint(1)') {
                            ctype = 'boolean';
                            clength = '';
                        } else {
                            ctype = 'tinyInteger';
                        }
                        break;
                    case 'smallint':
                        ctype='smallInteger';
                        break;
                    case 'varchar':
                        ctype='string';
                        break;
                    case 'char':
                        ctype='char';
                        break;
                    case 'tinytext':
                    case 'text':
                        ctype='text';
                        break;
                    case 'mediumtext':
                        ctype='mediumText';
                        break;
                    case 'longtext':
                        ctype='longText';
                        break;
                    case 'decimal':
                        ctype='decimal';
                        break;
                    case 'float':
                        ctype='float';
                        break;
                    case 'double':
                        ctype='double';
                        break;
                    case 'boolean':
                        ctype='boolean';
                        break;
                    case 'time':
                        ctype='time';
                        break;
                    case 'timestamp':
                        ctype='timestamp';
                        break;
                    case 'date':
                        ctype='date';
                        break;
                    case 'datetime':
                        ctype='dateTime';
                        break;
                    case 'enum':
                        ctype='enum';
                        break;
                    case 'varbinary':
                    case 'binary':
                        ctype='binary';
                        break;
                    case 'json':
                        ctype='json';
                        break;
                    case 'jsonb':
                        ctype='jsonb';
                        break;
                    case 'uuid':
                        ctype='uuid';
                        break;
                }

                if (values.Key == 'PRI'&&(type=='int'||type=='tinyint')) {
                    ctype = 'increments';
                }
                if (values.Key == 'PRI'&&(type=='bigint'||type=='mediumint')) {
                    ctype = 'bigIncrements';
                }
                addColumn(cname,ctype,clength,cdefault,cnullable,cunique,cunsigned,ccomment);
            }

            $(document).on('click','#migration-template-tables a',function(){
                ajax.getColumns('migrations-connection',$(this).text(),function(data){
                    //alert(JSON.stringify(data));
                    for(var i=0;i<data.length;i++){
                        if(data[i].Field!='created_at'&&data[i].Field!='updated_at'&&data[i].Field!='remember_token'&&data[i].Field!='soft_delete'){
                            dbToItem(data[i]);
                        }

                    }
                });
            });
        })(window.jQuery);
    </script>
@stop