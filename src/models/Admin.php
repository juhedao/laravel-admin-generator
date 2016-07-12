<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/2/17 17:03
 */
namespace Juhedao\LaravelAdminGenerator\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Admin extends Model{
    protected $connection = 'juhedao_admin_generator_sqlite';
    protected $table = 'admin';
    protected $fillable = ['name','password'];
    protected $hidden = ['password', 'remember_token'];
}