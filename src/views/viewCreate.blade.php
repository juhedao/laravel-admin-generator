@extends('juhedao-admin-generator-views::layouts.main')

@section('Page')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body form-inline">
                    Views根目录: <input type="text" id="views-path" value="{{$viewsRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" id="set-views-path" class="btn btn-default">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾
                     <div class="create-note">
                         Note: 设置为相对于Laravel根目录的路径，以/开始，以/结尾。
                     </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">创建Layout</div>
                <div class="panel-body form-inline">
                    <form method="post" id="create-layout-form">
                        Layout路径及名称: <input type="text" name="layout-name" id="layout-name" class="form-control" style="width: 320px">
                        &nbsp;&nbsp;从模板:
                        <select class="form-control" name="layout-template" id="layout-template" style="min-width: 160px">

                        </select>
                        &nbsp;&nbsp;
                        *请使用Laravel命名方式:dir.file

                        <div class="des">
                        <span>Layout备注：</span><textarea name="description" rows="2"></textarea>
                        </div>
                        <button type="submit" id="create-layout" class="btn btn-default">创建Layout</button>
                        <div id="layout-box" class="box" >

                        </div>
                    </form>
                    <div class="create-note">
                        Note: 模板文件均在src/template/layouts中，可以替换[[param_name]]形式的占位。
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">创建View</div>
                <div class="panel-body form-inline">
                    <form method="post"  id="create-view-form">
                    View路径及名称:
                    &nbsp; &nbsp;
                    <input type="text" id="view-name" name="view-name" class="form-control translation" style="width: 320px">
                    &nbsp; &nbsp;
                    是否是blade:
                    &nbsp; &nbsp;
                    <input type="radio" name="is-blade"  value="1" checked="checked"> 是
                    &nbsp; &nbsp;
                    <input type="radio" name="is-blade"  value="0" > 否
                    <br>
                    View类型:
                    &nbsp; &nbsp;
                    <input type="radio" name="view-type"  value="layout" checked="checked"> 使用Layout的View
                    &nbsp; &nbsp;
                    <input type="radio" name="view-type"  value="normal" > 不使用Layout的View
                    &nbsp; &nbsp;
                    <span id="view-type-layout">
                        扩展自Layout(extends):
                        <select class="form-control" id="view-extends" name="view-extends" style="min-width: 160px">

                        </select>
                    </span>
                    <span id="view-type-normal" style="display: none;">
                        从模板:
                        <select class="form-control" id="view-template" name="view-template" style="min-width: 160px">

                        </select>
                    </span>
                    <br>
                    <div class="des">
                        <span>View备注：</span><textarea name="description" rows="2"></textarea>
                    </div>
                    <button type="submit" id="create-view" class="btn btn-default">创建View</button>
                    <div id="view-box" class="box" >

                    </div>
                    </form>
                    <div class="create-note">
                        Note: 只可以extends本工具生成的layouts，按前后顺序生成section。模板文件均在src/template/views中，可以替换[[param_name]]形式的占位。
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">批量创建View</div>
                <div class="panel-body form-inline">
                    <form method="post" action="/admin/generator/batch-create-view-ajax"  id="batch-create-view-form">
                        <div class="row">
                            <div class="col-md-4">View前缀名,每行一个,包含.路径
                            <br>
                                <textarea id="batch-name" name="batch-name" class="translation" rows="12" style="width:100%;"></textarea>
                            </div>
                            <div class="col-md-8">
                                选择模板后缀
                                <br>
                                <ul class="batch" id="batch-template-box" style="list-style:none;">

                                </ul>
                            </div>
                            <div class="col-md-12"></div>
                        </div>
                        是否是blade:
                        &nbsp; &nbsp;
                        <input type="radio" name="is-blade"  value="1" checked="checked"> 是
                        &nbsp; &nbsp;
                        <input type="radio" name="is-blade"  value="0" > 否
                        <div class="des">
                            <span>统一备注：</span><textarea name="description" rows="2"></textarea>
                        </div>
                        <button type="submit" id="batch-create-view" class="btn btn-default">批量创建View</button>
                    </form>
                    <div class="create-note">
                        Note: 批量生成 前缀+后缀.blade.php，无法替换模板中的占位。
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.row-->
@stop

@section('FooterScript')
    <script type="text/javascript">
        (function ($) {
            //加载layouts
            function getLayouts(){
                ajax.getFiles('layout',function(data){
                    var options = [];
                    for(var i=0;i<data.length;i++){
                        options.push('<option value="'+data[i].path+'">'+data[i].path+'</option>');
                    }
                    $("#view-extends").html(options.join(''));
                });
            }
            getLayouts();
            //获取layout模板
            ajax.getTemplates('layouts',$('#layout-template'));
            //获取views模板
            ajax.getTemplates('views',$('#view-template'));
            //设置views的根目录
            $('#set-views-path').click(function(){
                var path = $.trim($('#views-path').val());
                if(path==''){
                    msg.warning('请输入Views根目录路径');
                    return false;
                }
                if(!/^\//.test(path)||!/\/$/.test(path)){
                    msg.warning('Views根目录路径必须以/并以/结尾');
                    return false;
                }
                ajax.setConfig('views-root',path);

            });
            //选择layout模板时获取占位
            $('#layout-template').change(function(){
                if(this.value!=''){
                    ajax.getTemplateParams('layouts/'+$(this).val(),$('#layout-box'));
                }else{
                    $('#layout-box').html('');
                    $('#layout-box').hide();
                }
            });
            //创建layout的option
            var optionCreateLayout = {
                type : 'post',
                url : '/admin/generator/create-layout-ajax',
                success : function (data) {
                    getLayouts();
                    if(data.result=='success'){
                        $('#layout-name').val('');
                        msg.success(data.msg);
                    }else{
                        msg.warning(data.msg);
                    }
                }
            };
            //创建Layout
            $('#create-layout').click(function(){
                if($.trim($('#layout-name').val())==''){
                    msg.warning('请输入Layout路径及名称');
                    return false;
                }
                if($('#layout-template').val()==''){
                    msg.warning('请选择Layout模板');
                    return false;
                }
                $('#create-layout-form').ajaxSubmit(optionCreateLayout);
                return false;
            });
            //视图类型选择
            $("input[name='view-type']").click(function(){
                if(this.value=='layout'){
                    $('#view-type-layout').show();
                    $('#view-type-normal').hide();
                    $("input[name='is-blade'][value='1']").prop('checked','checked');
                    $('#view-box').html('');
                    $('#view-box').hide();
                }else{
                    $('#view-type-layout').hide();
                    $('#view-type-normal').show();
                }
            });


            //选择view模板时获取占位
            $('#view-template').change(function(){
                if(this.value!=''){
                    ajax.getTemplateParams('views/'+$(this).val(),$('#view-box'));
                }else{
                    $('#view-box').html('');
                    $('#view-box').hide();
                }
            });
            var optionCreateView = {
                type : 'post',
                url : '/admin/generator/create-view-ajax',
                success : function (data) {
                    if(data.result=='success'){
                        $('#view-name').val('');
                        msg.success(data.msg);
                    }else{
                        msg.warning(data.msg);
                    }
                }
            };
            //创建view
            $('#create-view').click(function(){
                if($.trim($('#view-name').val())==''){
                    msg.warning('请输入view路径及名称');
                    return false;
                }
                if($("input[name='view-type']:checked").val()=='normal'){
                    if($('#view-template').val()==''){
                        msg.warning('请为view选择模板');
                        return false;
                    }
                }

                $('#create-view-form').ajaxSubmit(optionCreateView);
                return false;
            });


            ajax.getTemplates('views',null,function(data){
                var list=[];
                for(var i=0;i<data.length;i++){
                    var item = data[i];
                    var name = item.replace(/((?=[\x21-\x7e]+)[^A-Za-z0-9])/,'');
                    var html='<li>'
                                +'<input type="checkbox" value="'+item+'" name="batch-template-name[]">&nbsp;&nbsp;'
                                +'<input name="batch-suffix[s_'+name+']" class="form-control" placeholder="输入后缀建议用_开始" type="text">&nbsp;&nbsp;'
                                +item
                                +'</li>';
                    list.push(html);
                }
                $('#batch-template-box').html(list.join(''));
            });
            var optionBatchCreateView = {
                type : 'post',
                url : '/admin/generator/batch-create-view-ajax',
                success : function (data) {
                    if(data.result=='success'){
                        $('#layout-name').val('');
                        msg.success(data.msg.join('<br>'));
                    }else{
                        msg.warning(data.msg.join('<br>'));
                    }
                }
            };
           $('#batch-create-view').click(function(){
                if($.trim($('#batch-name').val())==''){
                    msg.warning('请填写批量生成的views前缀名');
                    return false;
                }
                var templateCheck = $("input[name='batch-template-name[]']:checked");
                if(templateCheck.length==0){
                    msg.warning('请位批量生成的views选择至少一个模板');
                    return false;
                }
                var msgs = ['有些选择模板没有填写前缀：'];
                for(var i=0;i<templateCheck.length;i++){
                    var suffix = $(templateCheck[i]).val().replace(/((?=[\x21-\x7e]+)[^A-Za-z0-9])/,'');
                    if($.trim($("input[name='batch-suffix[s_"+suffix+"]'").val())==''){
                        msgs.push('请为模板'+$(templateCheck[i]).val()+'添加前缀')
                    }
                }
                if(msgs.length>1){
                    msg.warning(msgs.join('<br>'));
                    return false;
                }

                $('#batch-create-view-form').ajaxSubmit(optionBatchCreateView);
                return false;
            });


        })(window.jQuery);
    </script>
@stop