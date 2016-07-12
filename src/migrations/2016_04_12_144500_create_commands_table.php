<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/12
 * Time: 14:46
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Contracts\Hashing\Hasher;

class CreateCommandsTable extends Migration
{
    public function up()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->create('commands', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name',255)->comment('命令名称');
            $table->text('template')->comment('模板');
            $table->text('note')->comment('说明');
            $table->comment = 'PHP artisan 命令表';
        });
        DB::connection('juhedao_admin_generator_sqlite')->table('commands')->insert(array(
            [
                'name'       => '生成迁移',
                'template'   => 'php artisan [(make:migration)] create_[{name}]_table [[--path=]] [[--create=]]',
                'note'       => ''
            ],
            [
                'name'       => '运行迁移',
                'template'   => 'php artisan [(migrate[[:refresh]][[:rollback]][[:reset]])] [[--force]] [[--path=]]',
                'note'       => ''
            ],
        ));
    }

    public function down()
    {
        Schema::connection('juhedao_admin_generator_sqlite')->drop('commands');
    }
}