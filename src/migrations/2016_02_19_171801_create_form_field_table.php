<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/1/20 21:23
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateFormFieldTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('form_field', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name',120)->unique()->comment = '字段名称';
            $table->boolean('is_custom')->default(false)->comment = '是否是自定义字段';
            $table->comment = '表单字段类型列表';
        });
        DB::connection('juhedao_admin_generator_sqlite')->table('form_field')->insert(array(
            array('name'=>'text'),
            array('name'=>'textarea'),
            array('name'=>'password'),
            array('name'=>'hidden'),
            array('name'=>'checkbox'),
            array('name'=>'radio'),
            array('name'=>'select'),
            array('name'=>'file'),
            array('name'=>'email'),
            array('name'=>'number'),
            array('name'=>'image'),
            array('name'=>'url'),
            array('name'=>'tel'),
            array('name'=>'search'),
            array('name'=>'color'),
            array('name'=>'date'),
            array('name'=>'datetime-local'),
            array('name'=>'month'),
            array('name'=>'range'),
            array('name'=>'time'),
            array('name'=>'week'),
            array('name'=>'choice')
        ));
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('form_field');
    }
}