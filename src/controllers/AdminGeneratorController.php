<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/2/16 15:47
 */
namespace Juhedao\LaravelAdminGenerator\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Guard;
use Mockery\Exception;
use View;
use Config;
use Hash;
use Request;
use File;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Juhedao\LaravelAdminGenerator\SqlToMigrations as SqlToMigrations;

use Juhedao\LaravelAdminGenerator\Models\Admin as Admin;

class AdminGeneratorController extends BaseController{

    private $isLogin = false;
    protected $files;

    function __construct(Filesystem $files){
        $adminid = session('adminid');
        $this->isLogin = isset($adminid);
        $this->files = $files;

    }

    public function getIndex(){
        $navName = '首页';
        if(!$this->isLogin){
            return \Redirect::to('/admin/generator/login');
        }
        $commandsList = \DB::connection('juhedao_admin_generator_sqlite')->table('commands')->get();
        $commands = [];
        foreach($commandsList as $item){
            preg_match_all("/\[\{[^\}]+\}\]/",$item->template,$replaces);
            preg_match_all("/\[\[[^\=\]]+\=\]\]/",$item->template,$params);
            preg_match_all("/\[\[[^\=\]]+\]\]/",$item->template,$selects);
            $item->replaces = $replaces[0];
            $item->params = $params[0];
            $item->selects =$selects[0];
            array_push($commands,$item);

        }

        $data = compact('navName','commands');


        /*$connection = $this->getConnection('default');
        $m = new SqlToMigrations($connection);
        dd($m);*/

        return View::make('juhedao-admin-generator-views::index',$data);
    }

    public function getCreateForm(){
        $navName = '生成表单';
        $formsRoot = $this->getOptions('forms-root');
        $validationsRoot = $this->getOptions('validations-root');
        $formsTemplate = $this->getOptions('forms-template');
        $rulesTemplate = \DB::connection('juhedao_admin_generator_sqlite')->table('validate_rules')->get();
        $data = compact('navName','formsRoot','validationsRoot','formsTemplate','rulesTemplate');

        return View::make('juhedao-admin-generator-views::createForm',$data);
    }

    public function anyFormSaveAjax(){
        $FRoot = $this->getOptions('forms-root');
        $VRoot = $this->getOptions('validations-root');
        $ft = $this->getOptions('forms-template');

        $data = SqlToMigrations::createForm($FRoot,$VRoot,$ft,\Input::all());

        $templatePath = __DIR__.'/../template/forms/default';
        $templateContent = $this->files->get($templatePath);

        $formHtml = \Input::get('form-htmls');

        $formRemarks = \Input::get('form-remarks');

        $templateContent = str_replace('{formStart}',$data['formStart'],$templateContent);
        $templateContent = str_replace('{formEnd}',$data['formEnd'],$templateContent);
        $templateContent = str_replace('{formContent}',$data['formContent'],$templateContent);
        $templateContent = str_replace('{formButton}',$data['formButton'],$templateContent);
        $templateContent = str_replace('{formHtml}',$formHtml,$templateContent);
        $templateContent = str_replace('{formRemarks}',$formRemarks,$templateContent);

        $formName = strtolower(trim(\Input::get('current-table'))).'_form';

        $formFullPath = base_path().$FRoot.$formName.'.blade.php';
        if($this->files->exists($formFullPath)) {
            return \Response::json(["done" => false, "msg" => $formFullPath . '已存在，请删除先！']);
        }else{
            $this->createFile($formFullPath,$templateContent);

            $formValidationPath  = base_path().$VRoot.$formName.'.php';
            if($this->files->exists($formValidationPath)) {
                return \Response::json(["done" => false, "msg" => $formValidationPath . '已存在，请删除先！']);
            }else {
                $this->createFile($formValidationPath,$data['validation']);
                return \Response::json(["done"=>true,"msg"=>$formFullPath.'创建成功！<br>'.$formValidationPath.'创建成功！']);
            }
        }
    }

    //-------------------------------------------------------------------routes管理开始-------------------------------------------------------------------------
    public function anyRoutesManage(){
        $navName = 'routes管理';
        $filesRoot = $this->getOptions('routes-main');
        $mainContent = $this->files->get(base_path().$filesRoot);
        preg_match_all("/require\_once\(([^)]+)\)/",$mainContent,$otherRoutesPath);
        


        $data = compact('navName','filesRoot');
        return View::make('juhedao-admin-generator-views::routesManage',$data);
    }
    //-------------------------------------------------------------------routes管理结束-------------------------------------------------------------------------

    //-------------------------------------------------------------------models开始-------------------------------------------------------------------------
    public function getModelsCreate(){
        $navName = '生成models';
        $filesRoot = $this->getOptions('models-root');
        $data = compact('navName','filesRoot');
        return View::make('juhedao-admin-generator-views::createModels',$data);
    }

    public function anyCreateModelAjax(){
        $modelRoot = $this->getOptions('models-root');

        $tn = \Input::get('model-table');
        $tableName = '';
        $para = explode('_',$tn);
        foreach($para as $item){
            $tableName .= ucfirst($item);
        }
        $content = SqlToMigrations::createModel(\Input::all(),$modelRoot,$tableName);

        $fullPath = base_path().$modelRoot.$tableName.'.php';
        if($this->files->exists($fullPath)){
            return \Response::json(["done"=>false,"msg"=>$fullPath.'已存在，请删除先！']);
        }else{
            $done=$this->createFile($fullPath,$content);
            return \Response::json(["done"=>true,"msg"=>$fullPath.'创建成功！']);
        }
    }
    //-------------------------------------------------------------------models结束-------------------------------------------------------------------------

    //-------------------------------------------------------------------seeds开始-------------------------------------------------------------------------
    public function getSeedsCreate(){
        $navName = '生成seeds';
        $filesRoot = $this->getOptions('seeds-root');
        $data = compact('navName','filesRoot');
        return View::make('juhedao-admin-generator-views::createSeeds',$data);
    }

    public function postSaveSeeds(){
        $seedsData = \Input::get('seeds-data');
        $tn = $seedsData['tableName'];
        $tableName = '';
        $para = explode('_',$tn);
        foreach($para as $item){
            $tableName .= ucfirst($item);
        }
        $content = SqlToMigrations::createSeeds($tableName,$seedsData);
        $seedsRoot = $this->getOptions('seeds-root');
        $fullPath = base_path().$seedsRoot.$tableName.'Seeder.php';
        if($this->files->exists($fullPath)){
            return \Response::json(["done"=>false,"msg"=>$fullPath.'已存在，请删除先！']);
        }else{
            $done=$this->createFile($fullPath,$content);
            $seedPath = base_path().$seedsRoot.'DatabaseSeeder.php';
            $seedContent = $this->files->get($seedPath);
            $seedContent = str_replace('Model::reguard();','$this->call('.$tableName.'Seeder::class);'.PHP_EOL.PHP_EOL.'        Model::reguard();',$seedContent);
            $this->files->put($seedPath,$seedContent);
            return \Response::json(["done"=>true,"msg"=>$fullPath.'创建成功！']);
        }
    }
    //-------------------------------------------------------------------seeds结束-------------------------------------------------------------------------

    //-------------------------------------------------------------------命令部分开始-------------------------------------------------------------------------
    //添加新的命令
    public function postCreateArtisanAjax(){
        $name = \Input::get('command-name');
        $template = \Input::get('command-template');
        $note = \Input::get('command-note');
        $row = \DB::connection('juhedao_admin_generator_sqlite')->table('commands')->insert(array(
            'name'       => $name,
            'template'   => $template,
            'note'       => $note
        ));
        if($row==0){
            return \Response::json(['result'=>'error','msg'=>'添加命令错误，请重试']);
        }else{
            return \Response::json(['result'=>'success','msg'=>'添加命令成功，请刷新页面']);
        }
    }
    //运行命令
    public function postRunArtisanAjax(){

        $argument = [];
        $template = \Input::get('template');
        $replaces = \Input::get('replace');
        if($replaces){
            if(isset($replaces['name'])){
                $argument['name'] = $replaces['name'];
            }
            foreach($replaces as $k=>$v){
                $template = str_replace("[{".$k."}]",$v,$template);
            }
        }
        $params = \Input::get('param');
        if($params){
            foreach($params as $k=>$v){
                if(!empty($v)){
                    $template = str_replace("[[--".$k."=]]",'--'.$k.'='.$v,$template);
                    $argument["--".$k] = $v;
                }else{
                    $template = str_replace("[[--".$k."=]]",'',$template);
                }
            }
        }
        $selects = \Input::get('selects');
        if($selects){
            foreach($selects as $k=>$v){
                $template = preg_replace("/\[\[([^\]]+$v)\]\]/","$1",$template);
                if(strstr($template,'--'.$v)){
                    $argument['--'.$v] = true;
                }
            }
        }
        $template = preg_replace("/\[\[[^\}\)\]]+\]\]/",'',$template);

        preg_match("/\[\([^\}\)\]]+\)\]/",$template,$method);

        $method = preg_replace("/\[|\]|\(|\)/",'',$method[0]);
        $template = preg_replace("/\[|\]|\(|\)|\{|\}/",'',$template);

        $result = \Artisan::call($method,$argument);

        if($result==0){
            return \Response::json(['result'=>'success','msg'=>"命令{$template}执行成功"]);
        }else{
            return \Response::json(['result'=>'success','msg'=>"命令{$template}执行失败，请重试"]);
        }

    }
    //-------------------------------------------------------------------命令部分结束-------------------------------------------------------------------------

    //-------------------------------------------------------------------migrations开始-------------------------------------------------------------------------
    //migrations创建页
    public function getMigrationsCreate(){
        $navName = '生成/修改migrations';
        $filesRoot = $this->getOptions('migrations-root');
        $migrationsConnection = $this->getOptions('migrations-connection');
        $data = compact('navName','filesRoot','migrationsConnection');
        return View::make('juhedao-admin-generator-views::migrationsCreate',$data);
    }

    public function postSaveMigrations(){
        $columns = \Input::get('columns');
        $tableName = \Input::get('table-name');
        $tableComment = \Input::get('table-comment');
        $isRememberToken = \Input::get('isRememberToken');
        $isSoftDeletes = \Input::get('isSoftDeletes');
        $timestampsType = \Input::get('timestampsType');

        $content = SqlToMigrations::covert($tableName,$columns,$isRememberToken,$isSoftDeletes,$timestampsType,$tableComment);

        $migrationsRoot = $this->getOptions('migrations-root');

        $fullPath = base_path().$migrationsRoot.date('Y_m_d_His').'_create_'.$tableName.'_table.php';

        $done=$this->createFile($fullPath,$content);
        return \Response::json("{'done':$done}");
    }
    //-------------------------------------------------------------------migrations结束-------------------------------------------------------------------------




    //-------------------------------------------------------------------controller部分开始-------------------------------------------------------------------------
    //创建controller页面
    public function getControllerCreate(){
        $navName = '生成controllers';
        if(!$this->isLogin){
            return \Redirect::to('/admin/generator/login');
        }
        $controllersRoot = $this->getOptions('controllers-root');
        $data = compact('navName','controllersRoot');
        return View::make('juhedao-admin-generator-views::controllerCreate',$data);
    }
    //ajax创建controller
    public function postCreateControllerAjax(){
        $controllerName = \Input::get('controller-name');
        $controllerNamespace = \Input::get('controller-namespace');
        $controllerPublic = \Input::get('controller-public');
        $controllerPrivate = \Input::get('controller-private');
        $description = \Input::get('description');
        $params = \Input::get('param');
        $template = __DIR__.'/../template/controllers/'.\Input::get('controller-template');
        $controllersRoot = $this->getOptions('controllers-root');
        $controllers = explode(PHP_EOL,$controllerName);

        $publicFunctions = explode(PHP_EOL,$controllerPublic);
        $privateFunctions = explode(PHP_EOL,$controllerPrivate);

        $content = $this->files->get($template);
        $content = str_replace('${namespace}',$controllerNamespace,$content);

        foreach($publicFunctions as $item){
            if(!empty($item)){
                $f = "    public function {$item}(){".PHP_EOL;
                $f .= "    }".PHP_EOL.PHP_EOL;
                $f .= "}";
                $content = preg_replace("/\}$/",$f,$content);
            }
        }

        foreach($privateFunctions as $item){
            if(!empty($item)){
                $f = "    private function {$item}(){".PHP_EOL;
                $f .= "    }".PHP_EOL.PHP_EOL;
                $f .= "}";
                $content = preg_replace("/\}$/",$f,$content);
            }
        }

        foreach($params as $k=>$v){
            $content = str_replace('[['.$k.']]',$v,$content);
        }

        $result = ['result'=>'success','msg'=>['所有Controller已创建成功:']];
        foreach($controllers as $item){
            if(!empty($item)){
                $item = str_replace('.',' ',$item);
                $item = ucwords(strtolower($item));
                $item =str_replace(' ','.',$item);
                $name =  substr(strrchr($item, '.'), 1);
                $content = str_replace('${name}',$name,$content);

                $nameSpace = preg_replace("/^\/|\/$/",'',$controllersRoot.$item);
                $nameSpace = str_replace('/','\\',$nameSpace);
                $nameSpace = substr($nameSpace,0,strrpos($nameSpace,'.'));
                $nameSpace = str_replace('app','App',$nameSpace);
                $content = preg_replace("/namespace[^\;]+\;/","namespace {$nameSpace};",$content);

                $path = $this->getFilePath($controllersRoot,$item.'Controller',".php");
                $fullPath = base_path().$path;
                if($this->files->exists($fullPath)){
                    $result['result'] = 'error';
                    $result['msg'][0] = '部分controller未创建成功：';
                    array_push($result['msg'],$path.'已存在，创建失败');
                }else{
                    $this->createFile($fullPath,$content);
                    array_push($result['msg'],$path.'创建成功');
                    \DB::connection('juhedao_admin_generator_sqlite')->table('files')->insert(array(
                        'type'          => 'controller',
                        'path'          => $path,
                        'description'   => $description,
                        'template'      => 'controllers/'.\Input::get('controller-template'),
                        'option'        => json_encode(compact('name','publicFunctions','privateFunctions','controllerNamespace','param')),
                        'created_at'    => date('Y-m-d h:i:s',time()),
                        'updated_at'    => date('Y-m-d h:i:s',time()),
                    ));
                }
            }
        }
        return \Response::json($result);
    }
    //-------------------------------------------------------------------controller部分结束-------------------------------------------------------------------------

    public function anyFormCreate(){;
        if(!$this->isLogin){
            return \Redirect::to('/admin/generator/login');
        }
        $navName = '生成表单';
        $tables = \DB::select('SHOW TABLES');
        $databaseName = \DB::getDatabaseName();
        $tableKey = 'Tables_in_'.$databaseName;
        $fieldTypes = $this->getFieldType();
        $data = compact('navName','tables','databaseName','tableKey','fieldTypes');
        return View::make('juhedao-admin-generator-views::formCreate',$data);
    }


    //-------------------------------------------------------------------视图部分开始-------------------------------------------------------------------------
    //生成视图页面
    public function getViewCreate(){
        $navName = '生成Views';
        $viewsRoot = $this->getOptions('views-root');
        $data = compact('navName','viewsRoot');
        return View::make('juhedao-admin-generator-views::viewCreate',$data);
    }

    //AJAX生成布局
    public function postCreateLayoutAjax(){
        $name = \Input::get('layout-name');
        $template = \Input::get('layout-template');
        $params = \Input::get('param');
        $description = \Input::get('description');

        $viewsRoot = $this->getOptions('views-root');
        $path = $this->getViewPath($viewsRoot,$name,true);
        $fullPath = base_path().$path;

        $msg = ['result'=>'error','msg'=>'创建失败，请重试'];

        if ($this->files->exists($fullPath)) {
            $msg['msg'] = '当前路径下的布局Layout已存在，请重新取名';
        }else{
            $this->createViewFile($fullPath,'/../template/layouts/',$template,$params);
            $msg['result'] = 'success';
            $msg['msg'] = "Layout: $path 创建成功";
            \DB::connection('juhedao_admin_generator_sqlite')->table('files')->insert(array(
                'type'          => 'layout',
                'path'          => $path,
                'description'   => $description,
                'template'      => $template,
                'option'        => json_encode([]),
                'created_at'    => date('Y-m-d h:i:s',time()),
                'updated_at'    => date('Y-m-d h:i:s',time()),
            ));
        }
        return \Response::json($msg);
    }

    //AJAX生成视图
    public function postCreateViewAjax(){
        $view_name = \Input::get('view-name');
        //是否为blade
        $is_blade = \Input::get('is-blade');
        $is_blade = (bool)$is_blade;
        //view类型
        $view_type = \Input::get('view-type');
        //view扩展自布局
        $view_extends = \Input::get('view-extends');
        //view从模板生成
        $view_template = \Input::get('view-template');
        $description = \Input::get('description');
        $params = \Input::get('param');

        $viewsRoot = $this->getOptions('views-root');
        $path = $this->getViewPath($viewsRoot,$view_name,$is_blade);
        $fullPath = base_path().$path;

        $msg = ['result'=>'error','msg'=>'创建失败，请重试！'];

        if ($this->files->exists($fullPath)) {
            $msg['msg'] = '当前路径下的View已存在，请重新取名';
        }else{
            if($view_type=='layout'){
                $this->createExtendsView($fullPath,$view_extends);
                $view_template = '';
            }else{
                $this->createViewFile($fullPath,'/../template/views/',$view_template,$params);
            }
            $msg['result'] = 'success';
            $msg['msg'] = "视图View: $path 创建成功";
            \DB::connection('juhedao_admin_generator_sqlite')->table('files')->insert(array(
                'type'          => 'view',
                'path'          => $path,
                'description'   => $description,
                'template'      => $view_template,
                'option'        => json_encode(compact('is_blade','view_type','view_extends','view_template')),
                'created_at'    => date('Y-m-d h:i:s',time()),
                'updated_at'    => date('Y-m-d h:i:s',time()),
            ));
        }
        return \Response::json($msg);
    }

    //批量生成view
    public function postBatchCreateViewAjax(){
        $batchName = \Input::get('batch-name');
        $batchName = explode("\n",$batchName);

        $templates = \Input::get('batch-template-name');
        $description = \Input::get('description');
        $suffixes = \Input::get('batch-suffix');
        $is_blade = \Input::get('is-blade');
        $is_blade = (bool)$is_blade;
        $viewsRoot = $this->getOptions('views-root');
        $msg = ['result'=>'success','msg'=>['所有的view均创建成功:']];
        foreach($batchName as $item){
            if($item!=''){
                foreach($templates as $template){
                    $suffixName = 's_'.preg_replace("/((?=[\x21-\x7e]+)[^A-Za-z0-9])/",'',$template);
                    $suffix = $suffixes[$suffixName];
                    $viewName=$item.$suffix;
                    $path = $this->getViewPath($viewsRoot,$viewName,$is_blade);
                    $fullPath = base_path().$path;
                    if ($this->files->exists($fullPath)) {
                        $msg['msg'][0] = '有未创建成功的模板:';
                        array_push($msg['msg'],$path.'已存在，未创建成功');
                        $msg['result'] = 'error';
                    }else{
                        $this->createViewFile($fullPath,'/../template/views/',$template,[]);
                        array_push($msg['msg'],$path.'已创建成功');
                        \DB::connection('juhedao_admin_generator_sqlite')->table('files')->insert(array(
                            'type'          => 'view',
                            'path'          => $path,
                            'description'   => $description,
                            'template'      => $template,
                            'option'        => json_encode(compact('is_blade')),
                            'created_at'    => date('Y-m-d h:i:s',time()),
                            'updated_at'    => date('Y-m-d h:i:s',time()),
                        ));
                    }
                }
            }
        }
        return \Response::json($msg);
    }

    //生成扩展blade
    private function createExtendsView($viewPath,$layoutPath){
        $html = "@extends('$layoutPath')".PHP_EOL.PHP_EOL;
        $layoutPath = base_path($layoutPath);
        $contents = File::get($layoutPath);
        $contents = str_replace(PHP_EOL,'',$contents);
        preg_match_all("[\@yield[^\)]+\'\)|\@section[^\@]+\@show]",$contents,$sections);
        foreach($sections[0] as $item){
            preg_match("/[\@yield|\@section]\(\'(.*?)\'\)/",$item,$sectionName);
            $html .= "@section('".$sectionName[1]."')".PHP_EOL;
            $html .= "<!-- $sectionName[1]-section -->".PHP_EOL;
            $html .= PHP_EOL;
            $html .= "<!-- /$sectionName[1]-section -->".PHP_EOL;
            $html .= "@stop".PHP_EOL;
            $html .= PHP_EOL.PHP_EOL;
        }
        $this->files->put($viewPath,$html);
    }

    //获取要生成的view路径
    private function getViewPath($dir,$name,$isBlade){
        $name=str_replace('.','/',$name);
        $name=str_replace('-','.',$name);
        $path = $dir.$name.($isBlade?'.blade.php':'.php');
        return $path;
    }

    //生成view文件
    private function createViewFile($path,$dir,$template,$params){
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
        $template = __DIR__.$dir.$template;
        $contents = File::get($template);
        if($params){
            foreach($params as $k=>$v){
                $contents=str_replace("[[$k]]",$v,$contents);
            }
        }
        $this->files->put($path,$contents);
    }

    //-------------------------------------------------------------------视图部分结束------------------------------------------------------------------------



    //-------------------------------------------------------------------公用部分开始-------------------------------------------------------------------------

    private function getConnection($optionName){
        if($optionName=='default'){
            return \DB::connection();
        }else if($optionName=='generator'){
            return \DB::connection('juhedao_admin_generator_sqlite');
        }else{
            $options = $this->getOptions($optionName);
            $options = explode(';',$options);
            $connectionOption = [];
            foreach($options as $item){
                if(!empty($item)){
                    $temp = explode('=',$item);
                    if(count($temp)>1){
                        $connectionOption[$temp[0]] = $temp[1];
                    }
                }
            }
            Config::set('database.connections.juhedao_admin_generator_other',$connectionOption);
            return \DB::connection('juhedao_admin_generator_other');
        }
    }

    public function getTablesAjax(){
        $optionName = \Input::get('option-name');

        $tables = [];

        try{
            $conn = $this->getConnection($optionName);
            $SQLMigration = new SqlToMigrations($conn);
            $tables = $SQLMigration->getTables();
        }catch(Exception $e){

        }
        return \Response::json($tables);
    }

    public function getColumnsAjax(){
        $optionName = \Input::get('option-name');
        $conn = $this->getConnection($optionName);
        $columns = [];
        $SQLMigration = new SqlToMigrations($conn);
        $tableName = \Input::get('table-name');
        try{
            $columns = $SQLMigration->getTableDescribes($tableName);
        }catch(Exception $e){

        }
        return \Response::json($columns);
    }

    //文件管理
    public function getFilesManage(){
        $navName = \Input::get('nav-name');
        $fileType = \Input::get('file-type');
        $fileRootName = \Input::get('file-root-name');
        $fileRoot = $this->getOptions($fileRootName);
        $data = compact('navName','fileType','fileRoot','fileRootName');
        return View::make('juhedao-admin-generator-views::filesManage',$data);
    }

    //获取模板
    public function getTemplatesAjax(){
        $path = \Input::get('path');
        $templates = File::allFiles(__DIR__.'/../template/'.$path);
        $files = [];
        foreach($templates as $template){
            array_push($files,$template->getRelativePathname());
        }
        return \Response::json($files);
    }

    //获取option
    private function getOptions($option_name){
        $option = \DB::connection('juhedao_admin_generator_sqlite')->table('options')->where('option_name',$option_name)->first();
        return $option?$option->option_value:"";
    }

    //设置option
    public function postOptionSetAjax(){
        $option_name = \Input::get('option_name');
        $option_value = \Input::get('option_value');
        $option = \DB::connection('juhedao_admin_generator_sqlite')->table('options')->where('option_name',$option_name)->first();
        $rows = 0;
        if(isset($option)){
            $rows = \DB::connection('juhedao_admin_generator_sqlite')->table('options')->where('option_name',$option_name)->update(['option_value'=>$option_value]);
        }else{
            $rows = \DB::connection('juhedao_admin_generator_sqlite')->table('options')->insert(['option_name'=>$option_name,'option_value'=>$option_value]);
        }

        $msg = sprintf('已经成功的将%s值设置为%s',$option_name,$option_value);
        if($rows==0){
            $msg = sprintf('在将%s值设置为%s过程中发生错误，请重试',$option_name,$option_value);
        }
        return \Response::json(['msg'=>$msg]);
    }

    //获取模板中占位
    public function getTemplateParamsAjax(){
        $path = \Input::get('path');
        $path = __DIR__.'/../template/'.$path;
        $content = $this->files->get($path);
        preg_match_all("/\[\[[^\]]+\]\]/",$content,$params);
        $result = array_unique($params);
        return \Response::json($params[0]);
    }

    //获得文件
    public function getFiles(){
        $type = \Input::get('type');
        $type = explode(',',$type);
        $files = \DB::connection('juhedao_admin_generator_sqlite')->table('files')->whereIn('type',$type)->orderBy('path','asc')->get();
        $result = [];

        foreach($files as $file){
            if ($this->files->exists(base_path().$file->path)){
                array_push($result,$file);
            }else{
                \DB::connection('juhedao_admin_generator_sqlite')->table('files')->where('id',$file->id)->delete();
            }
        }
        return \Response::json($result);
    }

    //组合文件路径
    private function getFilePath($dir,$name,$suffix){
        $name=str_replace('.','/',$name);
        $name=str_replace('-','.',$name);
        return $dir.$name.$suffix;
    }

    //创建文件
    private function createFile($path,$content){
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
        $this->files->put($path,$content);
    }

    //删除文件
    public function getDeleteFiles(){
        $ids = \Input::get('ids');
        $ids = explode(',',$ids);
        $files = \DB::connection('juhedao_admin_generator_sqlite')->table('files')->select('path')->whereIn('id',$ids)->get();
        foreach($files as $file){
            File::delete(base_path().$file->path);
        }
        \DB::connection('juhedao_admin_generator_sqlite')->table('files')->whereIn('id',$ids)->delete();
        return \Response::json(['result'=>'success']);
    }

    //复制文件
    public function getCopyFiles(){
        $id = \Input::get('id');
        $path = \Input::get('path');
        $sourcePath = base_path().$path;
        $type = \Input::get('type');
        $newName = \Input::get('new-name');
        $fileType = \Input::get('file-type');
        $newName = explode('|',$newName);
        $msg = ['result'=>'success','msg'=>['所有的文件均复制成功:']];
        $filesRoot = $this->getOptions($type);

        $suffix = substr($path, strrpos($path, '/')+1);
        $suffix = substr($suffix, strpos($suffix, '.')+1);
        $className =substr($sourcePath,strripos($sourcePath,'/')+1);
        $className =str_replace('.php','',$className);

        foreach($newName as $name){
            if(!empty($name)){

                if($fileType=='controller'||$fileType=='model'){
                    $name = str_replace('.',' ',$name);
                    $name = ucwords(strtolower($name));
                    $name =str_replace(' ','.',$name);
                    $name .= ucfirst($fileType);
                }

                $name=str_replace('.','/',$name);
                $name=str_replace('-','.',$name);
                $newPath = $filesRoot.$name.'.'.$suffix;
                $fullNewPath = base_path().$newPath;



                if($this->files->exists($fullNewPath)){
                    $msg['msg'][0] = '有部分文件未复制成功：';
                    array_push($msg['msg'],$newPath.'已存在，未创建成功');
                    $msg['result'] = 'error';
                }else{
                    if (!$this->files->isDirectory(dirname($fullNewPath))) {
                        $this->files->makeDirectory(dirname($fullNewPath), 0777, true, true);
                    }
                    $content = $this->files->get($sourcePath);
                    if($fileType=='controller'||$fileType=='model'){
                        $nameSpace = preg_replace("/^\/|\/$/",'',$filesRoot.$name);
                        $nameSpace = str_replace('/','\\',$nameSpace);
                        $nameSpace = substr($nameSpace,0,strrpos($nameSpace,'\\'));
                        $nameSpace = str_replace('app','App',$nameSpace);
                        $content = preg_replace("/namespace[^\;]+\;/","namespace {$nameSpace};",$content);
                        $newClassName = substr($name,strripos($name,'/'));
                        $newClassName = str_replace('/','',$newClassName);
                        $content = str_replace($className,$newClassName,$content);
                    }
                    $this->createFile($fullNewPath,$content);
                    array_push($msg['msg'],$newPath.'创建成功');
                    \DB::connection('juhedao_admin_generator_sqlite')->table('files')->insert(array(
                        'type'          => $fileType,
                        'path'          => $newPath,
                        'description'   => '复制',
                        'template'      => $path,
                        'option'        => json_encode([]),
                        'created_at'    => date('Y-m-d h:i:s',time()),
                        'updated_at'    => date('Y-m-d h:i:s',time()),
                    ));
                }
            }
        }
        return \Response::json($msg);
    }

    //获取表列
    public function getColumns($table){
        $tableColumns = \DB::select("SELECT column_name as field,data_type as type,column_comment as comment,character_maximum_length as str_length,numeric_precision as num_length from Information_schema.columns where TABLE_NAME='$table'");
        $columns = [];
        foreach($tableColumns as $column){
            array_push($columns,[
                'field'        => $column->field,
                'type'         => $column->type,
                'max'          => isset($column->str_length)?$column->str_length:$column->num_length,
                'comment'      => $column->comment,
                'fieldType'    => 'text'
            ]);
        }
        return \Response::json($columns);
    }

    //登录
    public function anyLogin(Request $request){
        /*if($this->isLogin){
            return \Redirect::to('/admin/generator');
        }*/
        $error = '';
        if (Request::isMethod('post')){
            $name = trim(\Input::get('name'));
            $password = trim(\Input::get('password'));
            $admin = Admin::where('name',$name)->first();
            if($admin){
                $secret = $admin->password;
                if(Hash::check($password,$secret)){
                    session(['adminid' => $admin->id]);
                    session(['adminname' => $admin->name]);
                    return \Redirect::to('/admin/generator');
                }else{
                    $error = '管理员密码错误！';
                }
            }else{
                $error = '当前管理员账号不存在！';
            }
        }
        return View::make('juhedao-admin-generator-views::login',compact('error'));
    }

    //sqlite管理页面
    public function anySqlite(){
        if(!$this->isLogin){
            return \Redirect::to('/admin/generator/login');
        }
        return View::make('juhedao-admin-generator-views::phpliteadmin');
    }

    private function getFieldType(){
        $fieldTypes = \DB::connection('juhedao_admin_generator_sqlite')
                    ->table('form_field')
                    ->orderByRaw('is_custom desc,id asc')->get();
        return $fieldTypes;
    }

    private function getFormClass($namespace,$className,$fields){
        return <<<EOT
<?php

namespace {namespace};

use Kris\LaravelFormBuilder\Form;

class {clasName} extends Form
{
    public function buildForm()
    {
        {{fields}}
    }
}
EOT;
    }

}