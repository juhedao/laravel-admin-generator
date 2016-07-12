<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/7
 * Time: 17:38
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('options', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('option_name',120)->comment = '名称';
            $table->string('option_value',255)->comment = '值';
        });
        DB::connection('juhedao_admin_generator_sqlite')->table('options')->insert(array(
            ['option_name'=>'views-root','option_value'=>'/resources/views/'],
            ['option_name'=>'controllers-root','option_value'=>'/app/Http/Controllers/'],
            ['option_name'=>'models-root','option_value'=>'/app/Http/Models/'],
            ['option_name'=>'migrations-root','option_value'=>'/database/migrations/'],
            ['option_name'=>'seeds-root','option_value'=>'/database/seeds/'],
            ['option_name'=>'routes-main','option_value'=>'/app/Http/routes.php'],
            ['option_name'=>'forms-root','option_value'=>'/resources/forms/'],
            ['option_name'=>'validations-root','option_value'=>'/config/validations/'],
            ['option_name'=>'forms-template','option_value'=>'    <div class="form-group"><br/>        <label for="{fieldId}">{fieldName}</label><br/>        {field}<br/>    </div> '],
            ['option_name'=>'migrations-connection','option_value'=>'driver=mysql;host=localhost;database=wei_maizibest_com;username=root;password=;charset=utf8;collation=utf8_unicode_ci;prefix=;strict=false;'],
        ));
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('options');
    }
}