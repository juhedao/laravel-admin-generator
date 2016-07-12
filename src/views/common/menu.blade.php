<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">

    <ul class="nav menu" style="margin-top: 10px">
        <li @if($navName=='首页')class="active"@endif><a href="/admin/generator/"><span class="glyphicon glyphicon-dashboard"></span> 首页</a></li>
        <li @if($navName=='生成Views')class="active"@endif><a href="/admin/generator/view-create"><span class="glyphicon glyphicon-book"></span> 生成views</a></li>
        <li @if($navName=='管理Views')class="active"@endif><a href="/admin/generator/files-manage?nav-name=管理views&file-type=view,layout&file-root-name=views-root"><span class="glyphicon glyphicon-list-alt"></span> 管理views</a></li>
        <li @if($navName=='生成controllers')class="active"@endif><a href="/admin/generator/controller-create"><span class="glyphicon glyphicon-thumbs-up"></span> 生成controllers</a></li>
        <li @if($navName=='管理controllers')class="active"@endif><a href="/admin/generator/files-manage?nav-name=管理controllers&file-type=controller&file-root-name=controllers-root"><span class="glyphicon glyphicon-certificate"></span> 管理controllers</a></li>
        <li  @if($navName=='生成/修改migrations')class="active"@endif><a href="/admin/generator/migrations-create"><span class="glyphicon glyphicon-align-justify"></span> 生成migrations</a></li>
        <li  @if($navName=='生成seeds')class="active"@endif><a href="/admin/generator/seeds-create"><span class="glyphicon glyphicon-edit"></span> 生成seeds</a></li>
        <li  @if($navName=='生成models')class="active"@endif><a href="/admin/generator/models-create"><span class="glyphicon glyphicon-magnet"></span> 生成models</a></li>
        <li @if($navName=='生成表单')class="active"@endif><a href="/admin/generator/create-form"><span class="glyphicon glyphicon-pencil"></span> 生成表单</a></li>
        <li @if($navName=='routes管理')class="active"@endif><a href="/admin/generator/routes-manage" style="display: none;"><span class="glyphicon glyphicon-pencil"></span> Routes管理</a></li>




    </ul>
</div><!--/.sidebar-->