<?php
/**
 * Created by 神奇的胖子 http://zhangxihai.cn.
 * User: Administrator
 * Date: 2016/4/18
 * Time: 17:38
 */
namespace Juhedao\LaravelAdminGenerator;
use \DB as DB;
use Illuminate\Support\Str;

class SqlToMigrations{
    private  $ignore = array('migrations');
    private  $database = "";
    private  $migrations = false;
    private  $schema = array();
    private  $selects = array('column_name as Field', 'COLUMN_COMMENT as Comment', 'column_type as Type', 'is_nullable as Null', 'column_key as Key', 'column_default as Default', 'extra as Extra', 'data_type as Data_Type');
    private  $instance;
    private  $up = "";
    private  $down = "";

    private $connection ;

    function __construct($connection){
        $this->connection = $connection;
        $this->database = $connection->getDatabaseName();
    }

    //获取所有表名
    public function getTables(){
        $tables = $this->connection->select('SELECT TABLE_NAME,TABLE_COMMENT FROM information_schema.tables WHERE Table_Type="'."BASE TABLE".'" and table_schema="' . $this->database . '"');
        return $tables;
    }

    //获取列信息
    public function getTableDescribes($table){
        return DB::table('information_schema.columns')
            ->where('table_schema', '=', $this->database)
            ->where('table_name', '=', $table)
            ->get($this->selects);
    }

    //获取有索引的表
    private function getForeignTables(){
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('CONSTRAINT_SCHEMA', '=', $this->database)
            ->where('REFERENCED_TABLE_SCHEMA', '=', $this->database)
            ->select('TABLE_NAME')->distinct()
            ->get();
    }

    //获取索引列信息
    private function getForeigns($table){
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('CONSTRAINT_SCHEMA', '=', $this->database)
            ->where('REFERENCED_TABLE_SCHEMA', '=', $this->database)
            ->where('TABLE_NAME', '=', $table)
            ->select('COLUMN_NAME', 'REFERENCED_TABLE_NAME', 'REFERENCED_COLUMN_NAME')
            ->get();
    }

    //生成migrations
    public static function covert($tableName,$columns,$isRememberToken,$isSoftDeletes,$timestampsType,$tableComment){
        $up = '';
        foreach($columns as $item){
            $up .= '            $table->'.$item['ctype'].'(';
            $up .= "'".$item['cname']."'";
            switch($item['ctype']){
                case 'integer':
                case 'bigInteger':
                case 'mediumInteger':
                case 'tinyInteger':
                case 'smallInteger':
                    if(!empty($item['clength'])){
                        $up .= ','.$item['clength'];
                    }
                    $up .= ')';
                    if($item['cunsigned']=='true'){
                        $up .= '->unsigned()';
                    }
                    break;
                case 'decimal':
                case 'float':
                case 'double':
                case 'string':
                case 'text':
                case 'mediumText':
                case 'longText':
                case 'char':
                    if(!empty($item['clength'])){
                        $up .= ','.$item['clength'];
                    }
                    $up .= ')';
                    break;
                case 'enum':
                    if(!empty($item['cdefault'])){
                        $up .= ','.$item['cdefault'];
                    }
                    $up .= ')';
                    break;
                default:
                    $up .= ')';
                    break;
            }

            if($item['ctype']!='increments'&&$item['ctype']!='bigIncrements'){
                if($item['cnullable']!='true'){
                    $up .= '->nullable()';
                }

                if($item['cunique']=='true'){
                    $up .= '->unique()';
                }
            }
            if(!empty($item['ccomment'])){
                $up .= "->comment = '".$item['ccomment']."'";
            }
            $up .= ';'.PHP_EOL;
        }
        if($isRememberToken){
            $up .= '            $table->rememberToken();'.PHP_EOL;
        }
        if($isSoftDeletes){
            $up .= '            $table->softDeletes();'.PHP_EOL;
        }
        if($timestampsType==1){
            $up .= '            $table->timestamps();'.PHP_EOL;
        }
        if($timestampsType==2){
            $up .= '            $table->nullableTimestamps();'.PHP_EOL;
        }
        $up .= '            $table->comment =' ."'".$tableComment."';";
        $tname = explode("_",$tableName);
        $className = 'Create';
        foreach($tname as $t){
            $className .= ucfirst($t);
        }
        $className .= 'Table';
        return self::getSchema($tableName,$up,$className);

    }

    private static function getSchema($tableName,$up,$className){
        return <<<EOT
<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class {$className} extends Migration
{
    public function up()
    {
        Schema::create('{$tableName}', function(Blueprint \$table)
        {
{$up}
        });
    }

    public function down()
    {
        Schema::drop('{$tableName}');
    }
}
EOT;
    }


    public static function createSeeds($tableName,$data){
        $run = '';
        $i = 0;
        foreach($data['items'] as $item){
            $repeat = $item['repeat'];
            $run .= PHP_EOL;
            $run .= '        $seeds_'.$i.' = 0;'.PHP_EOL;
            $run .= '        do {'.PHP_EOL;
            $run .= self::getSeedsInsert($tableName,$item['items']);
            $run .= '            $seeds_'.$i.'++;'.PHP_EOL;
            $run .= '        } while($seeds_'.$i.'<'.$repeat.');'.PHP_EOL.PHP_EOL;
            $i++;
        }
        return self::getSeedsMake($tableName,$run);
    }

    private static function getSeedsInsert($tableName,$data){
        $insert = '            DB::table(\''.$tableName.'\')->insert(['.PHP_EOL;
        foreach($data as $k=>$v){
            if(!empty($k)){
                $insert .= "                '".$k."'=>'".$v."',".PHP_EOL;
            }
        }
        $insert .= '            ]);'.PHP_EOL;
        return $insert;
    }

    private static function getSeedsMake($tableName,$run){
        return <<<EOT
<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class {$tableName}Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
{$run}
    }
}
EOT;

    }

    public static function createModel($params,$modelPath,$tableName){

        $modelPath = explode('/',$modelPath);

        $nc = [];
        foreach($modelPath as $item){
            if(!empty($item)){
                array_push($nc,ucfirst($item));
            }
        }

        $modelRoot = implode('\\',$nc);

        $modelNamespace = '';
        if(!empty($params['model-namespace'])){
            $mns = explode(PHP_EOL,$params['model-namespace']);
            foreach($mns as $item){
                $modelNamespace .= 'use '.$item.';'.PHP_EOL;
            }
        }

        $modelExtends = $params['model-extends'];



        $modelTableName = '    protected $table = \''.$params['model-table'].'\';';

        $modelImplements = '';
        if(!empty($params['model-implements'])){
            $modelImplements = 'implements '.str_replace('，','',$params['model-implements']);
        }

        $modelDates = '';

        if(isset($params['model-SoftDeletes'])){
            if(!empty($params['model-trait'])){
                $params['model-trait'] .= ',SoftDeletes';
            }else{
                $params['model-trait'] = 'SoftDeletes';
            }

            if(!empty($params['model-dates'])){
                $modelDates ='protected $dates = [\''.str_replace(',','\',\'',str_replace('，',',',$params['model-dates'])).'\']'.PHP_EOL;
            }else{
                $modelDates = 'deleted_at'.PHP_EOL;
            }
        }

        $modelTrait = '';
        if(!empty($params['model-trait'])){
            $modelTrait = '    use '.str_replace('，',',',$params['model-trait']).';';
        }





        $primaryKey = '    //protected $primaryKey = \'id\'';
        if(!empty($params['model-primaryKey'])){
            $modelImplements = '    protected $primaryKey = \''.$params['model-primaryKey'].'\';';
        }

        $modelTimestamps = '    //public $timestamps = false;';
        if(!isset($params['model-timestamps'])){
            $modelTimestamps = '    public $timestamps = false;';
            if(!empty($params['model-dateFormat'])){
                $modelTimestamps .= '    protected $dateFormat=\''.$params['model-dateFormat'].'\''.PHP_EOL;
            }
        }

        $modelFillable = '    protected $fillable = [];';
        if(isset($params['model-fillable'])){
            $fillable = $params['model-fillable'];
            if(is_array($fillable)){
                $modelFillable = '    protected $fillable = [\''.implode('\',\'',$fillable).'\'];';
            }else{
                if(!empty($fillable)){
                    $modelFillable = '    protected $fillable = [\''.str_replace(',','\',\'',str_replace('，',',',$fillable)).'\'];';
                }
            }
        }

        $modelHidden = '    //protected $hidden = [];';
        if(isset($params['model-hidden'])){
            $hidden = $params['model-hidden'];
            if(is_array($hidden)){
                $modelHidden = '    protected $hidden = [\''.implode('\',\'',$hidden).'\'];';
            }else{
                if(!empty($hidden)){
                    $modelFillable = '    protected $hidden = [\''.str_replace(',','\',\'',str_replace('，','',$hidden)).'\'];';
                }
            }
        }

        $modelGuarded = '    //protected $guarded = [];';
        if(isset($params['model-guarded'])){
            $guarded = $params['model-guarded'];
            if(is_array($guarded)){
                $modelGuarded = '    protected $guarded = [\''.implode('\',\'',$guarded).'\'];';
            }else{
                if(!empty($hidden)){
                    $modelGuarded = '    protected $guarded = [\''.str_replace(',','\',\'',str_replace('，','',$guarded)).'\'];';
                }
            }
        }

        $scopeMore = $params['model-scope-more'];
        $modelScope = '';
        if(!empty($scopeMore)){
            $scopeMore = explode(PHP_EOL,$scopeMore);
            foreach($scopeMore as $item){
                $modelScope .= '    /*'.PHP_EOL;
                $modelScope .= '    *scope'.ucfirst($item).PHP_EOL;
                $modelScope .= '    *params:'.PHP_EOL;
                $modelScope .= '    *描述:'.PHP_EOL;
                $modelScope .= '    */:'.PHP_EOL;
                $modelScope .= '    public function scope'.ucfirst($item).'($query){'.PHP_EOL;
                $modelScope .= '        //请完善代码'.PHP_EOL;
                $modelScope .= '        return $query;'.PHP_EOL;
                $modelScope .= '    }'.PHP_EOL.PHP_EOL;
            }
        }


        if(isset($params['model-scope'])){
            $scope = $params['model-scope'];
            foreach($scope as $item){
                $modelScope .= '    /*'.PHP_EOL;
                $modelScope .= '    *scope'.ucfirst($item).PHP_EOL;
                $modelScope .= '    *params:'.PHP_EOL;
                $modelScope .= '    *描述:'.PHP_EOL;
                $modelScope .= '    */:'.PHP_EOL;
                $modelScope .= '    public function scope'.ucfirst($item).'($query,$value){'.PHP_EOL;
                $modelScope .= '        //请完善代码'.PHP_EOL;
                $modelScope .= '        return $query->where(\''.$item.'\',\'=\',\'$value\');'.PHP_EOL;
                $modelScope .= '    }'.PHP_EOL.PHP_EOL;
            }
        }

        $hooks = '';

        if(isset($params['model-event'])){
            foreach($params['model-event'] as $item){
                $hooks .= '    public function '.$item.'(){'.PHP_EOL;
                $hooks .= '        return true;'.PHP_EOL;
                $hooks .= '    }'.PHP_EOL.PHP_EOL;
            }
        }

        $ardentAttribute = '';
        $ardentPurge = '';
        $ardentHash = '';
        $isArdent = isset($params['isArdent']);
        if($isArdent){

            if(isset($params['model-feature'])){
                $ardentFeature = $params['model-feature'];
                $ardentAttribute = '    //Ardent Attribute'.PHP_EOL;
                foreach($ardentFeature as $item){
                    $ardentAttribute .= '    public $'.$item.' = true;'.PHP_EOL;
                }
            }

            if(isset($params['model-purge'])){
                $purge = $params['model-purge'];
                if(is_array($purge)){
                    $purge = implode(',',$purge);
                }
                if(!empty($purge)){
                    $ardentPurge = '    //Ardent清除多余字段'.PHP_EOL;
                    $ardentPurge .= '    function __construct($attributes = array()) {'.PHP_EOL;
                    $ardentPurge .= '        parent::__construct($attributes);'.PHP_EOL;
                    $ardentPurge .= '        $this->purgeFilters[] = function($key) {'.PHP_EOL;
                    $ardentPurge .= '            $purge = [\''.str_replace(',','\',\'',str_replace('，',',',$purge)).'\'];'.PHP_EOL;
                    $ardentPurge .= '            return ! in_array($key, $purge);'.PHP_EOL;
                    $ardentPurge .= '        }'.PHP_EOL;
                    $ardentPurge .= '    }'.PHP_EOL;
                }
            }

            if(isset($params['model-hash'])) {
                $mohash = $params['model-hash'];
                if (is_array($mohash)) {
                    $mohash = implode(',', $mohash);
                }
                if(!empty($mohash)){
                    $ardentHash = '    //Ardent 加密字段'.PHP_EOL;
                    $ardentHash .= '    public static $passwordAttributes  = [\''.str_replace(',','\',\'',str_replace('，',',',$mohash)).'\'];';
                }
            }

            if(isset($params['model-hooks'])){
                $hooks = '    //Ardent hooks'.PHP_EOL;
                foreach($params['model-hooks'] as $item){
                    $hooks .= '    public function '.$item.'(){'.PHP_EOL;
                    $hooks .= '        return true;'.PHP_EOL;
                    $hooks .= '    }'.PHP_EOL.PHP_EOL;
                }
            }


        }

        return self::modelMake($modelRoot,$modelNamespace,$modelExtends,$tableName,$modelTableName,$modelImplements,$modelTrait,$primaryKey,$modelTimestamps,$modelFillable,$modelHidden,$modelGuarded,$ardentAttribute,$ardentPurge,$ardentHash,$hooks);

    }

    public static function modelMake($modelPath,$modelNamespace,$modelExtends,$tableName,$modelTableName,$modelImplements,$modelTrait,$primaryKey,$modelTimestamps,$modelFillable,$modelHidden,$modelGuarded,$ardentAttribute,$ardentPurge,$ardentHash,$hooks){
        return <<<EOT
<?php

namespace {$modelPath};

use Illuminate\Database\Eloquent\Model;
{$modelNamespace}

class {$tableName} extends {$modelExtends} {$modelImplements} {
{$modelTrait}

{$modelTableName}
{$primaryKey}
{$modelTimestamps}

{$modelFillable}
{$modelGuarded}
{$modelHidden}

{$ardentAttribute}

{$ardentHash}

{$ardentPurge}

{$hooks}
}
EOT;

    }


    public static function createForm($FRoot,$VRoot,$ft,$params){
        $formRows = json_decode($params['formList']);
        $formValidations = json_decode($params['validationList']);


        $VRoot = str_replace('config/','',$VRoot);
        $VRoot = preg_replace('/^\//','',$VRoot);
        $VRoot = str_replace('/','.',$VRoot);

        $formAttribute = [];
        $formList = [];
        $hiddens = [];

        if(isset($params['form-csrf'])){
            array_push($hiddens,'{!! Form::token() !!}');
        }


        foreach($formRows as $item){
            $fn = trim($item->fieldName);
            $fl = trim($item->fieldLabel);
            $fa = trim($item->fieldAttribute);
            if(empty($fl)) {
                $fl=$fn;
            }
            $formAttribute[$fn] = $fl;
            $ftype = $item->fieldType;
            $row = $ft;
            if($ftype!='hidden'){
                $row = str_replace('{fieldId}',$fn,$row);
                $row = str_replace('{fieldName}','{{ config(\''.$VRoot.strtolower(trim($params['current-table'])).'_form.attributes.'.$fn.'\') }}',$row);
                $row = str_replace('{field}',self::getFormBuilder($ftype,$fn,$fa),$row);
                $row = str_replace('<br/>',PHP_EOL,$row);
                array_push($formList,$row);
            }else{
                $h = self::getFormBuilder($ftype,$fn,null);
                array_push($hiddens,$h);
            }


        }



        $rules = [];
        $messages = [];
        foreach($formValidations as $item){
            $ruleField = trim($item->field);
            $ruleList = [];
            foreach($item->list as $it){
                $ruleName = trim($it->rule);
                $ruleValue = trim($it->value);
                $ruleMessage = trim($it->message);
                $rl = $ruleName.(empty($ruleValue)?'':''.$ruleValue);
                array_push($ruleList,$rl);
                if(!empty($ruleMessage)){
                    $messages[$ruleField.'.'.$ruleName] = $ruleMessage;
                }
            }
            $rules[$ruleField] = implode('|',$ruleList);
        }

        $builderMethod = empty(trim($params['form-model']))?'open(':'model($'.lcfirst(trim($params['form-model'])).',';

        $formMore = [];

        if(!empty(trim($params['current-table']))){
            $formMore['id'] = strtolower(trim($params['current-table'])).'-form';
            $formMore['name'] = strtolower(trim($params['current-table'])).'-form';
        }

        if(!empty(trim($params['form-method']))){
            $formMore['method'] = trim($params['form-method']);
        }

        if(!empty(trim($params['form-url']))){
            $formMore['url'] = trim($params['form-url']);
        }

        if(!empty(trim($params['form-route']))){
            $formMore['route'] = trim($params['form-route']);
        }

        if(!empty(trim($params['form-action']))){
            $formMore['action'] = trim($params['form-action']);
        }

        if(!empty(trim($params['form-class-name']))){
            $formMore['class'] = trim($params['form-class-name']);
        }


        $formMorex = self::getArray($formMore);

        $formMorex = implode(',',$formMorex);

        array_push($hiddens,''.PHP_EOL);
        $formStart = '{!! Form::'.$builderMethod.'['.$formMorex.']) !!}';
        $formEnd = '{!! Form::close() !!}';
        $formContent = '    '.implode(PHP_EOL.'    ',$hiddens);
        $formContent .= implode(PHP_EOL.PHP_EOL,$formList);


        $formButton = '{!! Form::'.$params['form-submit'].'('.$params['form-submit-param'].') !!}';
        if(isset($params['form-reset'])){
            $formButton .= '{!! Form::reset('.$params['form-reset-param'].') !!}';
        }

        $rules = self::getArray($rules);
        $rules = implode(','.PHP_EOL.'        ',$rules);
        //dd($messages);
        $messages = self::getArray($messages);

        $messages = implode(','.PHP_EOL.'        ',$messages);

        $formAttribute = self::getArray($formAttribute);
        $formAttribute = implode(','.PHP_EOL.'        ',$formAttribute);

        $validation = '<?php'.PHP_EOL.PHP_EOL;
        $validation .= '//对应表单:'.$formMore['name'].PHP_EOL;
        $validation .= '//表单存储地址:'.$FRoot.$formMore['name'].'.php'.PHP_EOL.PHP_EOL;
        $validation .= 'return ['.PHP_EOL.PHP_EOL;
        $validation .= '    //验证规则'.PHP_EOL;
        $validation .= '    \'rules\' => ['.PHP_EOL;
        $validation .= '        '.$rules.PHP_EOL;
        $validation .= '    ],'.PHP_EOL.PHP_EOL;
        $validation .= '    //验证信息'.PHP_EOL;
        $validation .= '    \'messages\' => ['.PHP_EOL;
        $validation .= '        '.$messages.PHP_EOL;
        $validation .= '    ],'.PHP_EOL.PHP_EOL;
        $validation .= '    //友好字段名称'.PHP_EOL;
        $validation .= '    \'attributes\' => ['.PHP_EOL;
        $validation .= '        '.$formAttribute.PHP_EOL;
        $validation .= '    ]'.PHP_EOL.PHP_EOL;
        $validation .= '];'.PHP_EOL;

        return compact('formStart','formEnd','formContent','formButton','validation');

    }

    public static function getArray($arr){
        $list = [];
        foreach($arr as $k=>$v){
            $tp = '\''.$k.'\' => \''.$v.'\'';
            array_push($list,$tp);
        }
        return $list;
    }

    public static function getFormBuilder($ft,$fn,$fa){
        $fb = '';
        switch($ft){
            case 'password':
                $fb = '{!! Form::password(\''.$fn.'\','.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'image':
                $fb = '{!! Form::image(\'\',\''.$fn.'\','.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'file':
                $fb = '{!! Form::file(\''.$fn.'\','.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'checkbox':
                $fb = '{!! Form::checkbox(\''.$fn.'\',\'\',null,'.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'radio':
                $fb = '{!! Form::radio(\''.$fn.'\',\'\',null,'.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'select':
                $fb = '{!! Form::select(\''.$fn.'\',[],null,'.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            case 'selectRange':
                $fb = '{!! Form::selectRange(\''.$fn.'\',\'\',\'\',null,'.str_replace("'placeholder'=>''",'',$fa).') !!}';
                break;
            default:
                $fb = '{!! Form::'.$ft.'(\''.$fn.'\',null'.(isset($fa)?','.$fa:'').') !!}';
                break;
        }
        return $fb;
    }

}