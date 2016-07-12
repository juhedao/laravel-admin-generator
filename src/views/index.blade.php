@extends('juhedao-admin-generator-views::layouts.main')

@section('Page')
    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default chat">
                <div class="panel-heading" id="accordion"><span class="glyphicon glyphicon-comment"></span> PHP
                    Artisan命令
                </div>
                <div class="panel-body">
                    <ul>
                        @foreach($commands as $command)
                            <li class="left clearfix">
                                <form action="/admin/generator/run-artisan-ajax" class="command-form" method="post">
                                <span class="chat-img pull-left">
									<button  class="img-circle run-artisan" type="submit" id="btn-chat">执行</button>
								</span>
                                <div class="chat-body clearfix">

                                    <div class="header">
                                        <input type="hidden" name="template" value="{{$command->template}}"/>
                                        <strong class="primary-font">{{$command->name}}</strong>
                                    </div>
                                    @if(count($command->replaces)!=0 || count($command->params)!=0 || count($command->selects)!=0)
                                    <p>
                                        @if(count($command->replaces)!=0)
                                        name:&nbsp;&nbsp;
                                        @foreach($command->replaces as $item)
                                            {{preg_replace("/\{|\}|\[|\]/",'',$item)}} <input type="text" name="replace[{{preg_replace("/\{|\}|\[|\]/",'',$item)}}]">&nbsp;&nbsp;
                                        @endforeach
                                        <br>
                                        @endif
                                        @if(count($command->params)!=0)
                                        可选设置：&nbsp;&nbsp;
                                        @foreach($command->params as $item)
                                            {{preg_replace("/\[|\]/",'',$item)}} <input type="text" name="param[{{preg_replace("/\[|\]|\-|=/",'',$item)}}]">&nbsp;&nbsp;
                                        @endforeach
                                        <br>
                                        @endif
                                        @if(count($command->selects)!=0)
                                        可选参数：&nbsp;&nbsp;
                                        @foreach($command->selects as $item)
                                            {{preg_replace("/\[|\]/",'',$item)}} <input type="checkbox" style="width: 15px;height:15px;" name="selects[]" value="{{preg_replace("/\[|\]|\-|\:/",'',$item)}}" >&nbsp;&nbsp;
                                        @endforeach
                                        <br>
                                        @endif
                                    </p>
                                    @endif
                                    <p>
                                        <small style="margin-left: 0">{{$command->template}}</small>
                                    </p>
                                    @if(!empty($command->note))
                                    <p style="font-size: 12px">
                                        {{$command->note}}
                                    </p>
                                    @endif
                                </div>
                                </form>
                            </li>
                        @endforeach
                        <div class="clearfix"></div>
                    </ul>
                </div>


            </div>

        </div><!--/.col-->

        <div class="col-md-4">

            <div class="panel panel-blue">
                <div class="panel-heading dark-overlay"><span class="glyphicon glyphicon-check"></span>添加新的Artisan命令</div>
                <div class="panel-body" style="background: #fff;color:#5f6468">
                    <form id="create-artisan-form">
                    命令名称：<input type="text" class="form-control" id="command-name" name="command-name">
                    <br>
                    命令模板: <textarea class="form-control" id="command-template" name="command-template"></textarea>
                    <br>
                    命令说明: <textarea class="form-control" id="command-note" name="command-note"></textarea>
                    <br>
                    <button id="create-artisan" class="btn btn-primary btn-md" id="btn-todo">添加</button>
                    </form>
                </div>
                <div class="panel-footer" style="color:#5f6468;font-size: 12px;color:#777">
                    格式说明:
                    <br>
                    [{name}] 命令中的name值
                    <br>
                    [(make::model)] 使用[()]包含命令本身，不包括php artisan部分
                    <br>
                    [[--path=]] 表示有值参数，=号后不要跟值
                    <br>
                    [[--force]] 表示可选参数
                </div>
            </div>

        </div><!--/.col-->
    </div><!--/.row-->
@stop

@section('FooterScript')
    <script type="text/javascript">
        (function ($) {

            var optionCreateArtisan = {
                type : 'post',
                url : '/admin/generator/create-artisan-ajax',
                success : function (data) {
                    if(data.result=='success'){
                        msg.success(data.msg);
                    }else{
                        msg.warning(data.msg);
                    }
                }
            };
            $('#create-artisan').click(function(){
                if($.trim($('#command-name').val())==''){
                    msg.warning('请Artisan命令名称');
                    return false;
                }
                if($.trim($('#command-template').val())==''){
                    msg.warning('请Artisan命令模板');
                    return false;
                }

                $('#create-artisan-form').ajaxSubmit(optionCreateArtisan);
                return false;
            });

            $('.run-artisan').click(function(){
                var form = $(this).parents('form');
                $.ajax({
                    type : 'post',
                    url : '/admin/generator/run-artisan-ajax',
                    data : form.serialize(),
                    dataType : 'json'
                }).done(function(data){
                    if(data.result=='success'){
                        msg.success(data.msg);
                    }else{
                        msg.warning(data.msg);
                    }
                }).fail(function(data){
                    document.write(JSON.stringify(data));
                });
                return false;
            });

        })(window.jQuery);
    </script>
@stop