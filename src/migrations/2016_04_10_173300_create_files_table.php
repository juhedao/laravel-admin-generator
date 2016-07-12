<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/4/10 17:34
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateFilesTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('files', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('type',120)->default('view')->comment = '文件类型';
            $table->string('path',500)->comment = '文件名称';
            $table->text('option')->comment = '其它参数';
            $table->text('description')->comment = '文件描述';
            $table->string('template',500)->comment = '模板';
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('files');
    }
}