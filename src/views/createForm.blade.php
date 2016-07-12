@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
@stop

@section('HeaderScript')
@stop

@section('Page')
    <div class="row" style="min-width: 1500px">

        <div class="col-md-10" style="padding-right: 0">
            <div class="panel panel-default">


                <div style="padding: 15px">
                    <form id="form-info">
                    <span style="display: inline-block;width:50px">表单名:</span> <input type="text" class="form-control" style="display: inline-block;width:320px" name="current-table" id="current-table"/>
                        &nbsp;&nbsp;
                        <span style="display: inline-block;width:50px">CSS类:</span> <input type="text" class="form-control" style="display: inline-block;width:320px" value="form-horizontal" name="form-class-name" id="form-class-name"/>
                    <div style="height: 10px;"></div>
                    <span style="display: inline-block;width:50px">Url:</span> <input style="width:320px;display: inline-block" class="form-control" type="text" id="form-url" name="form-url">
                    &nbsp;&nbsp;
                    <span style="display: inline-block;width:50px">Method:</span> <select id="form-method" name="form-method" style="display: inline-block;width:160px;" class="form-control">
                        <option value="post" selected>post</option>
                        <option value="get">get</option>
                        <option value="put">put</option>
                        <option value="delete">delete</option>
                    </select>
                    &nbsp;&nbsp;
                    <span style="display: inline-block;width:105px">字段通用CSS类:</span> <input value="form-control" style="width:320px;display: inline-block" class="form-control" type="text" data-old="form-control" id="form-class" name="form-class">
                    <div style="height: 10px"></div>
                    <span style="display: inline-block;width:50px">Model:</span> <input style="width:320px;display: inline-block" class="form-control" type="text" id="form-model" name="form-model">
                    &nbsp;&nbsp;
                    <span style="display: inline-block;width:50px">Route:</span> <input style="width:320px;display: inline-block" class="form-control" type="text" id="form-route" name="form-route">
                    &nbsp;&nbsp;
                    <span style="display: inline-block;width:50px">Action:</span> <input style="width:320px;display: inline-block" class="form-control" type="text" id="form-action" name="form-action">
                    <div style="height: 10px"></div>
                    CSRF: <input style="display: inline-block" type="checkbox" id="form-csrf" value="1" name="form-csrf">
                    &nbsp;&nbsp;
                    Submit: <input style="display: inline-block" type="radio"  value="submit" checked  name="form-submit">
                    &nbsp;Button: <input style="display: inline-block" type="radio"   value="button"  name="form-submit">
                    &nbsp; <input style="display: inline-block;width:320px" type="text" id="form-submit-param" name="form-submit-param" value="'提交',['class'=>'form-control',id=>'save']" class="form-control cnn">



                    &nbsp;&nbsp;
                    Reset: <input style="display: inline-block" type="checkbox" id="form-reset"  value="1"  name="form-reset">
                    &nbsp; <input style="display: inline-block;width:320px" type="text" id="form-reset-param" name="form-reset-param" value="'重置',['class'='form-control',id=>'reset']" class="form-control cnn">
                    &nbsp;&nbsp;
                    </form>
                </div>
                <div class="panel panel-body">
                    <table class="table table-bordered table-hover" style="border: solid 1px #ddd; border-radius: 3px">
                        <thead>
                        <tr>
                            <th style="width: 25px"><input  checked type="checkbox" class="checkall"> </th>
                            <th>字段ID</th>
                            <th>字段Label</th>
                            <th>表单类型</th>
                            <th>数据类型</th>
                            <th>其它参数</th>
                            <th style="width: 90px">排列/操作</th>
                        </tr>
                        </thead>
                        <tbody id="clist">

                        </tbody>
                    </table>
                    <br>
                    <button id="form-builder-create" class="btn btn-info">添加自定义Form Builder字段</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button id="create-messages" class="btn btn-default">生成验证信息,上方表单有变动时则需重新点击此按钮</button>
                    <br>
                    <br>
                    <table class="table table-bordered table-hover" style="border: solid 1px #ddd; border-radius: 3px">
                        <thead>
                        <tr>
                            <th style="width: 25px"><input checked type="checkbox" class="checkall"> </th>
                            <th style="width:180px">字段ID</th>
                            <th>验证规则</th>
                            <th style="width: 90px">操作</th>
                        </tr>
                        </thead>
                        <tbody id="rule-list">

                        </tbody>
                    </table>
                    <br>
                    <span style="display:inline-block;width:80px">附加HTML：</span>
                    &nbsp;&nbsp;
                    <textarea id="form-htmls" name="form-htmls" class="form-control"  rows="5"></textarea>
                    <br>
                    <span style="display:inline-block;width:80px">表单备注：</span>
                    &nbsp;&nbsp;
                    <textarea id="form-remarks" name="form-remarks" class="form-control"  rows="3"></textarea>
                    <br>
                    <button id="create-form" class="btn btn-success">生成表单和验证规则</button>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body form-inline">

                    Validations根目录: <input type="text"  value="{{$validationsRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" data-option-type="validations-root" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾 前面不要加/config
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body form-inline">

                    表单行模板:<br> <textarea rows="5"  class="form-control" style="width:80%">{!! str_replace('<br/>',PHP_EOL,$formsTemplate)  !!} </textarea>
                    <br>
                    <button type="button" data-option-type="forms-template" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *
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

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body form-inline">

                    Forms根目录: <input type="text"  value="{{$formsRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" data-option-type="forms-root" class="btn btn-default set-option">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾
                </div>
            </div>
        </div>

    </div>
<select id="builder-type" style="display: none">
    <option value="text">text</option>
    <option value="select">select</option>
    <option value="checkbox">checkbox</option>
    <option value="radio">radio</option>
    <option value="password">password</option>
    <option value="selectRange">selectRange</option>
    <option value="hidden">hidden</option>
    <option value="number">number</option>
    <option value="email">email</option>
    <option value="url">url</option>
    <option value="file">file</option>
    <option value="image">image</option>
</select>

    <div id="rules-template" style="display: none">
        <?php $i=1; ?>
        @foreach($rulesTemplate as $item)
            <li @if($i>4) class="hid" style="display: none" @endif style="padding:10px;border-bottom:dotted 1px #f7ecb5; "><span style="display: inline-block; width:160px"><input class="rule-name" type="checkbox" value="{{$item->name}}"> <span style="font-weight:600;color:#ff4433">{{$item->name}}</span> </span>  @if(!empty($item->demo)) 值：<input class="form-control rule-value" placeholder="{{$item->demo}}" type="text" style="width:260px; display: inline-block;height:25px;font-weight: 400;color:#ff4433">@else <span style="display: inline-block;width:290px"></span>@endif 自定义信息: <input class="form-control rule-message" value=":attribute" type="text" style="width:260px; display: inline-block;height:25px;font-weight: 400;color:#ff4433"> <span style="font-size:10px;font-weight:400;color:#999;display:inline-block;">{{$item->describe}}</span></li>
            @if($i==4)
                <div class="rule-more" style="background: #f5f5f5;cursor: pointer;padding:15px; background:#f7ecb5;" data-status="0">展开更多验证规则 ∨</div>
            @endif       
                <?php $i++; ?>
        @endforeach
    </div>
@stop

@section('FooterScript')
    <script src="/assets/juhedao/admin-generator/js/bootstrap-datepicker.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table-zh-CN.min.js"></script>
    <script type="text/javascript">
        (function ($) {
            $('#create-form').click(function(){

                if($.trim($('#current-table').val())==''){
                    msg.warning('表单名不能为空！');
                    return false;
                }

                if($('#clist').find('.sele:checked').length==0){
                    msg.warning('没有要生成的表单字段，或者你并没有勾选！');
                    return false;
                }


                var formEleList = $('#clist tr');
                var formList = [];
                for(var i=.0;i<formEleList.length;i++){
                    var item = $(formEleList[i]);
                    if(item.find(':checkbox').prop('checked')){
                        var fieldName = $.trim(item.find("input[name='field-name']").val());
                        var fieldLabel = $.trim(item.find("input[name='field-label']").val());
                        var fieldType = $.trim(item.find("select[name='field-type']").val());
                        var fieldAttribute = $.trim(item.find("textarea[name='field-attribute']").val());
                        formList.push({'fieldName':fieldName,'fieldType':fieldType,'fieldLabel':fieldLabel,'fieldAttribute':fieldAttribute});
                    }

                }

                var validationEleList = $('#rule-list tr');
                var validationList = [];
                for(var i=0;i<validationEleList.length;i++){
                    var item = ruleEleList = $(validationEleList[i]).find('li');
                    var fieldName = $.trim($(validationEleList[i]).find("input[name='field-name']").val());
                    if($(validationEleList[i]).find(':checkbox').prop('checked')) {
                        var ruleList = [];
                        for(var a=0;a<item.length;a++){
                            var ruleEle = $(item[a]);
                            if(ruleEle.find(':checkbox').prop('checked')) {
                                var ruleName = $.trim(ruleEle.find('.rule-name').val());
                                var ruleValue = $.trim(ruleEle.find('.rule-value').val());
                                if(typeof ruleValue == 'undefined'){
                                    ruleValue = '';
                                }
                                var ruleMessage = $.trim(ruleEle.find('.rule-message').val());
                                if(ruleMessage == ':attribute'){
                                    ruleMessage = '';
                                }
                                ruleList.push({'rule':ruleName,'value':(ruleValue==''?'':':'+ruleValue),'message':ruleMessage})
                            }
                        }
                        if(ruleList.length>0){
                            var tp={'field':fieldName,'list':ruleList};
                            validationList.push(tp);
                        }

                    }
                }

                console.log(JSON.stringify(formList));
                console.log(JSON.stringify(validationList));

                $.ajax({
                    'url' : '/admin/generator/form-save-ajax',
                    'type' : 'post',
                    'data' : $('#form-info').serialize()+'&formList='+JSON.stringify(formList)+'&validationList='+JSON.stringify(validationList)+'&form-htmls='+$('#form-htmls').val()+'&form-remarks='+$('#form-remarks').val(),
                    'dataType' : 'json'
                }).done(function(data){
                    if(data.done){
                        msg.success(data.msg);
                    }else{
                        msg.warning(data.msg);
                    }
                }).fail(function(data){
                    document.write(JSON.stringify(data));
                });

            });




            //设置根目录
            $('.set-option').click(function(){
                var option = $(this).parent().find("input[type='text']");

                var path = option.val();
                var obj=$(this);

                if(typeof path=='undefined'){
                    path = $(this).parent().find("textarea").val();
                }

                path = path.replace(/\n/g,'<br/>');

                var optionType = $(this).data('option-type');
                if(path==''){
                    msg.warning('请输入要修改的值');
                    return false;
                }
                if(optionType == 'migrations-root'){
                    if(!/^\//.test(path)||!/\/$/.test(path)){
                        msg.warning('根目录路径必须以/并以/结尾');
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

                var tn = tableName.split('_');
                var modelName= [];
                for(var i=0;i<tn.length;i++){
                    modelName.push(tn[i].replace(/(\w)/,function(v){return v.toUpperCase()}));
                }
                $('#current-table').val(tableName);
                $('#form-model').val(modelName.join(''));

                ajax.getColumns('default',tableName,function(data){
                    var className = $('#form-class').val();
                    var clist = []
                    var n=1;
                    for(var i=0;i<data.length;i++) {
                        var item = data[i];
                        var cname = item.Field;
                        var clabel = item.Comment.replace(/\([^\)]+\)/g,'').replace(/\(/g,'').replace(/\)/g,'');
                        var ctype = item.Type;
                        if(cname != 'deleted_at' && cname != 'created_at' && cname != 'updated_at'){
                            var ht= '<tr>';
                            ht += '<td><input class="form-control sele" type="checkbox" checked></td>';
                            ht += '<td><input class="form-control field-name" name="field-name" type="text" style="width:120px" value="'+cname+'"> </td>';
                            ht += '<td><input class="form-control field-label" name="field-label" type="text" style="width:120px" value="'+clabel+'"> </td>';
                            ht += '<td><select name="field-type" class="form-control builder-type"><option value="text">text</option></select></td>';
                            ht += '<td>'+ctype+'</td>';
                            ht += '<td><textarea name="field-attribute" placeholder="[\'xxx\'=>\'sss\']" class="form-control cnn">[\'class\'=>\''+className+'\',\'placeholder\'=>\'\']</textarea></td>';
                            ht += '<td>';
                            ht += '<i title="上移" class="glyphicon glyphicon-arrow-up up" onclick="cup(this);return false;" style="color:#0c9;cursor: pointer;padding-top: 10px"></i>';
                            ht += '&nbsp;&nbsp;<i title="下移" class="glyphicon glyphicon-arrow-down down" onclick="cdown(this);return false;"  style="color:#f90;cursor: pointer"></i>';
                            ht += '&nbsp;&nbsp;<i title="删除" class="glyphicon glyphicon-remove del"   style="cursor: pointer"></i>';
                            ht += '</td></tr>';
                            clist.push(ht);
                            n++;
                        }
                    }
                    $('#clist').append(clist.join(''));

                });

            });

            $('#form-builder-create').click(function(){
                var className = $('#form-class').val();

                var ht= '<tr>';
                ht += '<td><input class="form-control sele" type="checkbox" checked></td>';
                ht += '<td><input class="form-control field-name" name="field-name" type="text" style="width:120px" value=""> </td>';
                ht += '<td><input class="form-control field-label" name="field-label" type="text" style="width:120px" value=""> </td>';
                ht += '<td><select name="field-type" class="form-control builder-type"><option value="text">text</option></select></td>';
                ht += '<td>自定义</td>';
                ht += '<td><textarea name="field-attribute" placeholder="[\'xxx\'=>\'sss\']" class="form-control cnn">[\'class\'=>\''+className+'\',\'placeholder\'=>\'\']</textarea></td>';
                ht += '<td>';
                ht += '<i title="上移" class="glyphicon glyphicon-arrow-up up" onclick="cup(this);return false;" style="color:#0c9;cursor: pointer;padding-top: 10px"></i>';
                ht += '&nbsp;&nbsp;<i title="下移" class="glyphicon glyphicon-arrow-down down" onclick="cdown(this);return false;"  style="color:#f90;cursor: pointer"></i>';
                ht += '&nbsp;&nbsp;<i title="删除" class="glyphicon glyphicon-remove del"   style="cursor: pointer"></i>';
                ht += '</td></tr>';
                $('#clist').append(ht);
            });

            $('#create-messages').click(function(){
                var row = $('#clist tr');

                var ht = '';
                var rules = $('#rules-template').html();
                for(var i=0;i<row.length;i++){
                    var obj = $(row[i]);
                    var fieldName = obj.find('.field-name').val();
                    var fieldLabel = obj.find('.field-label').val();
                    if(obj.find(':checkbox').prop('checked')){

                        ht += '<tr class="mr">';
                        ht += '<td><input class="form-control sele" type="checkbox" checked></td>';
                        ht += '<td style="font-size:20px; text-align: center">'+fieldName+'<br>('+fieldLabel+')'+'<input name="field-label" value="'+fieldLabel+'" type="hidden"><input name="field-name" value="'+fieldName+'" type="hidden"></td>';
                        ht += '<td><ul>'+rules+'</ul></td>'
                        ht += '<td>';
                        ht += '<i title="上移" class="glyphicon glyphicon-arrow-up up" onclick="cup(this);return false;" style="color:#0c9;cursor: pointer;padding-top: 10px"></i>';
                        ht += '&nbsp;&nbsp;<i title="下移" class="glyphicon glyphicon-arrow-down down" onclick="cdown(this);return false;"  style="color:#f90;cursor: pointer"></i>';
                        ht += '&nbsp;&nbsp;<i title="删除" class="glyphicon glyphicon-remove del"   style="cursor: pointer"></i>';
                        ht += '</td></tr>';

                    }

                }

                $('#rule-list').html(ht);
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

            $(document).on('click','#clist .del',function(){
                $(this).parents('tr').remove();
            });

            $(document).on('click','#clist .up',function(){
                var p = $(this).parents('tr');
                var prev = p.prev();
                if(typeof prev.html()=='undefined'){
                    alert('这已经在最顶部了！');
                }else{
                    prev_html=prev.html();
                    p_html= p.html();
                    p.html(prev_html);
                    prev.html(p_html);
                }
            });

            $(document).on('click','#clist .down',function() {
                var p = $(this).parents('tr');
                var prev = p.next();
                if(typeof prev.html()=='undefined'){
                    alert('这已经在最底部了！');
                }else{
                    prev_html=prev.html();
                    p_html= p.html();
                    p.html(prev_html);
                    prev.html(p_html);
                }
            });

            $('.checkall').click(function(){
                $($(this).parents('table').find('.sele')).prop('checked',this.checked?'checked':'');
            });

            $(document).on('mousedown','#clist .builder-type',function(){
                var sel = $(this).val();
                if($(this).find('option').length<=1){
                    if($(this).html($('#builder-type').html())){
                        $(this).val(sel);
                    }
                }
            });

            $(document).on('click','.rule-more',function(){
                if($(this).data('status')==0){
                    $(this).parents('ul').find('.hid').show();
                    $(this).data('status',1);
                    $(this).html('收起更多验证规则 ∧');
                }else{
                    $(this).parents('ul').find('.hid').hide();
                    $(this).data('status',0);
                    $(this).html('展开更多验证规则 ∨');
                }

            });

            $('#form-class').blur(function(){
                var oldv = $(this).data('old');
                var newv = $(this).val();
                $(this).data('old',newv);
                $('.cnn').each(function(){
                    var v = $(this).val();

                    v = v.replace(oldv,newv);
                    $(this).val(v);
                });
            });

        })(window.jQuery);
    </script>
@stop