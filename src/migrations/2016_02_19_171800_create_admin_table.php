<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/1/20 21:23
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateAdminTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('admin', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name',80)->unique()->comment = '管理员名称';
            $table->string('password',80)->comment = '管理员密码';
            $table->timestamps();
            $table->comment = '管理员表';
        });
        DB::connection('juhedao_admin_generator_sqlite')->table('admin')->insert(array(
            'name'        => 'admin',
            'password'    => Hash::make('admin888'),
            'updated_at'  => '2016-2-17 17:47:00',
            'created_at'  => '2016-2-17 17:47:00'
        ));
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('admin');
    }
}