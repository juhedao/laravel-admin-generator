@extends('juhedao-admin-generator-views::layouts.main')

@section('Page')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body form-inline">
                    Controllers根目录: <input type="text" id="controllers-root" value="{{$controllersRoot}}" class="form-control" style="width: 320px">
                    &nbsp;&nbsp;
                    <button type="button" id="set-controllers-root" class="btn btn-default">修改</button>
                    &nbsp;&nbsp;
                    *请以/开头 以/结尾

                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">创建Controller</div>
                <div class="panel-body form-inline">
                    <form action="/admin/generator/create-controller-ajax" id="create-controller-form" method="post">
                        <div class="des">
                            <span style="width: 100px">Controller名：</span><textarea class="translation" style="padding:10px" placeholder="* 一行一个，不带Controller" id="controller-name" name="controller-name" rows="5"></textarea>
                        </div>
                        <div class="des sp">
                            <span style="width: 100px">use<br>namespace：</span><textarea style="padding:10px" placeholder="可选 一行一个，以;分号结束" name="controller-namespace" rows="2"></textarea>
                        </div>
                        <div class="des">
                            <span style="width: 100px">变量：</span>
                            <div class="row" style="display: inline-block;width: 80%;margin-top: -15px;margin-bottom: -15px;margin-left: -20px">
                                <div class="col-md-4">
                                    public <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-public" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    protected <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-protected" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    private <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-private" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="des sp">
                            <span style="width: 100px">方法：</span>
                            <div class="row" style="display: inline-block;width: 80%;margin-top: -15px;margin-bottom: -15px;margin-left: -20px">
                                <div class="col-md-4">
                                    public <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-public" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    protected <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-protected" rows="2"></textarea>
                                </div>
                                <div class="col-md-4">
                                    private <br><textarea style="padding:10px;width:100%;" placeholder="可选 一行一个" name="controller-private" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="des">
                            <span style="width: 100px">备注：</span><textarea style="padding:10px"  name="description" rows="2"></textarea>
                        </div>
                        <div class="des sp">
                            <span style="width: 100px">Controller模板：</span><select class="form-control" id="controller-template" name="controller-template"></select>
                        </div>

                        <br>
                        <button type="submit" id="create-controller" class="btn btn-default">创建Controller</button>
                        <div id="controller-box" class="box" >

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!--/.row-->
@stop

@section('FooterScript')
    <script type="text/javascript">
        (function ($) {
            ajax.getTemplates('controllers',$('#controller-template'));
            $('#set-controllers-root').click(function(){
                var path = $.trim($('#controllers-root').val());
                if(path==''){
                    msg.warning('请输入Views根目录路径');
                    return false;
                }
                if(!/^\//.test(path)||!/\/$/.test(path)){
                    msg.warning('Views根目录路径必须以/并以/结尾');
                    return false;
                }
                ajax.setConfig('controllers-root',path);
            });
            $('#controller-template').change(function(){
                if(this.value!=''){
                    ajax.getTemplateParams('controllers/'+$(this).val(),$('#controller-box'));
                }else{
                    $('#controller-box').html('');
                    $('#controller-box').hide();
                }
            });
            $('#create-controller').click(function(){
                if($.trim($('#controller-name').val())==''){
                    msg.warning('请填写controller名称');
                    return false;
                }
                if($.trim($('#controller-template').val())==''){
                    msg.warning('请选择controller模板');
                    return false;
                }
                ajax.submitForm($('#create-controller-form'),'/admin/generator/create-controller-ajax');
                return false;
            });

        })(window.jQuery);
    </script>
@stop