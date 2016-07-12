<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel Magic Gui @yield('Title')</title>

<link href="/assets/juhedao/admin-generator/css/bootstrap.min.css" rel="stylesheet">
<link href="/assets/juhedao/admin-generator/css/datepicker3.css" rel="stylesheet">
<link href="/assets/juhedao/admin-generator/css/styles.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="/assets/juhedao/admin-generator/js/html5shiv.js"></script>
<script src="/assets/juhedao/admin-generator/js/respond.min.js"></script>
<![endif]-->
<script src="/assets/juhedao/admin-generator/js/jquery-1.11.1.min.js"></script>
<script src="/assets/juhedao/admin-generator/js/bootstrap.min.js"></script>
<script src="/assets/juhedao/admin-generator/js/main.js"></script>
<script src="/assets/juhedao/admin-generator/js/jquery.form.js"></script>

@yield('HeaderScript')

</head>

<body>
<div  class="row" id="prompt">
    <div class="col-md-12">
        <div id="msg-success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" onclick="$(this).parent().hide();"><span aria-hidden="true">&times;</span></button>
            <strong>成功!&nbsp;&nbsp;</strong> <span class="text"></span>
        </div>
        <div id="msg-warning" class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" onclick="$(this).parent().hide();"><span aria-hidden="true">&times;</span></button>
            <strong>错误!&nbsp;&nbsp;</strong> <span class="text"></span>
        </div>
    </div>
</div>
@include('juhedao-admin-generator-views::common.header')

@include('juhedao-admin-generator-views::common.menu')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

    @include('juhedao-admin-generator-views::common.nav')

    @yield('Page')




</div>

@include('juhedao-admin-generator-views::common.footer')
@yield('FooterScript')

</body>

</html>