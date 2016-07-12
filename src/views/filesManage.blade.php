@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
    <link href="/assets/juhedao/admin-generator/css/bootstrap-table.css" rel="stylesheet">
    <script type="text/javascript">
        function getFullTemplate(value,row){
            if(row.description!='复制'){
                return row.type+'s/'+value;
            }else{
                return value;
            }

        }
        function getExtends(value){
            var option = JSON.parse(value);
            return option.view_extends;
        }
        function operateFormatter(value,index){
            return '<a href="javascript:void(0);" data-id='+value+' data-toggle="modal" data-target="#copy-view" class="copy-view">复制它</a>';
        }
        window.operateEvents = {
            'click .copy-view': function (e, value, row, index) {
                $('#file-id').html(row.id);
                $('#file-path').html(row.path);
                $('#file-type').val(row['type']);
            }
        };
    </script>
@stop

@section('Page')
    <div class="row">

        <div class="col-lg-12">
            <div class="panel panel-default">

                <div class="panel-body form-inline">

                    注意: 仅可以管理使用本工具创建的{{$fileType}}。
                    <br>
                    {{$fileType}}根目录: <input type="text" id="files-root" value="{{$fileRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" id="set-files-root" class="btn btn-default">修改</button>
                    &nbsp;&nbsp;
                    *请以/结尾 修改根目录用于复制{{$fileType}}
                    <br>
                    <div id="toolbar">
                        <button id="delete-files" class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> 删除选中的{{$fileType}}</button>
                    </div>
                    <table id="files-list-table" data-pagination="true" data-page-size="120" data-toolbar="#toolbar" data-toggle="table"  data-show-refresh="true" data-url="/admin/generator/files?type={{$fileType}}" data-show-toggle="true" data-show-columns="true" data-search="true"   data-click-to-select="true" data-sort-name="path" data-sort-order="asc">
                        <thead>
                        <tr>
                            <th data-field="state" data-checkbox="true" ></th>
                            <th data-field="type"  data-sortable="true" data-width="90">类型</th>
                            <th data-field="path" data-sortable="true">路径及名称</th>
                            <th data-field="template" data-sortable="true" data-formatter="getFullTemplate">模板</th>
                            <th data-field="option" data-sortable="true"  data-formatter="getExtends">Extends</th>
                            <th data-field="description" data-sortable="true" data-width="180">备注</th>
                            <th data-field="created_at" data-sortable="true" data-width="180">创建日期</th>
                            <th data-field="id" data-formatter="operateFormatter" data-events="operateEvents" data-width="75">操作</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="copy-view">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">复制{{$fileType}}</h4>
                </div>
                <div class="modal-body">
                    <p>ID:<span id="file-id"></span>  <input type="hidden" value="view" id="file-type">
                        <br>
                        文件:<span id="file-path"></span>
                        <br>
                        新文件名及路径，每行一个
                        <br>
                        <textarea id="new-name" rows="5" class="form-control"></textarea></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button id="copy-files" type="button" class="btn btn-primary">立刻复制{{$fileType}}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@stop

@section('FooterScript')
    <script src="/assets/juhedao/admin-generator/js/bootstrap-datepicker.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table-zh-CN.min.js"></script>
    <script type="text/javascript">
        (function ($) {
            //设置根目录
            $('#set-files-root').click(function(){
                var path = $.trim($('#files-root').val());
                if(path==''){
                    msg.warning('请输入{{$fileType}}根目录路径');
                    return false;
                }
                if(!/^\//.test(path)||!/\/$/.test(path)){
                    msg.warning('{{$fileType}}根目录路径必须以/并以/结尾');
                    return false;
                }
                ajax.setConfig('{{$fileRootName}}',path);
            });

            var table = $('#files-list-table');
            //删除选中的view
            $('#delete-files').click(function(){
                var ids = $.map(table.bootstrapTable('getAllSelections'), function (row) {
                    return row.id;
                });
                if(ids==''){
                    msg.warning('请选择要删除的{{$fileType}}');
                    return false;
                }
                ajax.deleteFiles(ids,function(data){
                    table.bootstrapTable('remove', {
                        field: 'id',
                        values: ids
                    });
                    msg.success('选中的文件删除成功')
                });
            });
            $('#copy-files').click(function(){
                var id = $('#file-id').html();
                var path = $('#file-path').html();
                var fileType = $('#file-type').val();
                var new_name = $('#new-name').val().replace(/[\n|\r]/g,'|');
                ajax.copyFiles('{{$fileRootName}}',id,path,new_name,fileType,function(){
                    $('#files-list-table').bootstrapTable('refresh',{
                        url: '/admin/generator/files?type={{$fileType}}'
                    });
                });

            });
        })(window.jQuery);
    </script>
@stop