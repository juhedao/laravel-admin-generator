@extends('juhedao-admin-generator-views::layouts.main')

@section('HeaderScript')
    <link href="/assets/juhedao/admin-generator/css/bootstrap-table.css" rel="stylesheet">
    <script type="text/javascript">
        function select_full(obj){
            if($(obj).data('full')==0){
                $(obj).html($('#field-types').html());
                $(obj).unbind("click");
                $(obj).data('full',true);
            }
        }
        function select_field_type(){
            var select = '<select data-full="0" onmousedown="select_full(this);" class="form-control"">'
                       + '<option value="text">text</option>'
                       + '</select>';
            return select;
        }
    </script>
@stop

@section('Page')
    <div class="row">
        <div class="col-md-9" style="padding-right: 0">
            <div class="panel panel-default">
                <div class="panel-heading">字段选择 当前表: <a id="current-table" href="#"><?php echo $tables[0]->{$tableKey}; ?></a> </div>
                <div class="panel-body">
                    <table id="table-columns" data-toggle="table" data-url="/admin/generator/columns/<?php echo $tables[0]->{$tableKey}; ?>"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="column" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="field" data-checkbox="true" ></th>
                            <th data-field="field" data-sortable="true">字段名称</th>
                            <th data-field="comment"  data-sortable="true">备注</th>
                            <th data-field="type"  data-sortable="true" data-width="90">字段类型</th>
                            <th data-field="max"  data-sortable="true" data-width="90">长度</th>
                            <th data-field="type" switchable="true" data-formatter="select_field_type">表单类型</th>
                        </tr>
                        </thead>
                    </table>
                    <hr>
                    <button type="button" class="btn btn-primary">为上方选择的字段生成表单</button>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">生成表单的字段</div>
                <div class="panel-body">
                </div>
            </div>
        </div><!--/.col-->
        <div class="col-md-3">
            <div class="panel panel-blue">
                <div class="panel-heading dark-overlay" style="text-align: left"><span class="glyphicon glyphicon-check"></span>数据库: {{$databaseName}}</div>
                <div class="panel-body">
                    <ul class="todo-list">
                        @foreach($tables as $key=>$value)
                        <li class="todo-list-item">
                            <div data-table="<?php echo $value->{$tableKey}; ?>" class="checkbox table-select">
                                {{($key+1)}}
                                <label  for="checkbox"><?php echo $value->{$tableKey}; ?></label>
                            </div>

                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div><!--/.col-->

    </div><!--/.row-->
    <select id="field-types" style="display: none">
        @foreach($fieldTypes as $type)
            <option value="{{$type->name}}">{{$type->name}} @if($type->is_custom)自定义@endif</option>
        @endforeach
    </select>
@stop

@section('FooterScript')
    <script src="/assets/juhedao/admin-generator/js/bootstrap-datepicker.js"></script>
    <script src="/assets/juhedao/admin-generator/js/bootstrap-table.js"></script>
    <script type="text/javascript">
        (function ($) {
            $('.table-select').click(function(){
                var table=$(this).data('table');
                $('#current-table').html(table);
                $('#table-columns').bootstrapTable('refresh',{
                    url: '/admin/generator/columns/'+table
                });
            });
        })(window.jQuery);
    </script>
@stop