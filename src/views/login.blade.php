<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录Laravel Admin Generator</title>

    <link href="/assets/juhedao/admin-generator/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/juhedao/admin-generator/css/datepicker3.css" rel="stylesheet">
    <link href="/assets/juhedao/admin-generator/css/styles.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="/assets/juhedao/admin-generator/js/html5shiv.js"></script>
    <script src="/assets/juhedao/admin-generator/js/respond.min.js"></script>
    <![endif]-->

</head>

<body style="overflow: hidden">

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        @if(!empty($error))
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">错误:</span>
            {{$error}}
        </div>
        @endif
        <div class="login-panel panel panel-default">

            <div class="panel-heading">登录 Laravel Admin Generator</div>

            <div class="panel-body">
                <form role="form" method="post" action="/admin/generator/login">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="管理员账号" name="name"  autofocus="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="密码" name="password" type="password" value="">
                        </div>
                        <input type="submit" class="btn btn-primary" value="登录"/>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

@include('juhedao-admin-generator-views::common.footer')

</body>

</html>