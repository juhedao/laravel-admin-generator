<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/2/16 15:30
 */
namespace Juhedao\LaravelAdminGenerator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Guard;
use Config;

class AdminGeneratorServiceProvider extends ServiceProvider {
    protected $defer = false;

    public function boot(){
        $this->handleConnection();
        $this->handleAssets();
        $this->handleConfigs();
        $this->handleMigrations();
        $this->handleModels();
        $this->handleViews();
        $this->handleControllers();
        $this->handleRoutes();
        //$this->handleAdminAuth();
    }

    public function register(){

    }

    private function handleConnection(){
        $juhedao_admin_generator_sqlite = [
            'driver'   => 'sqlite',
            'database' => __DIR__.'/sqlite/AdminGenerator.db',
            'prefix'   => '',
        ];
        Config::set('database.connections.juhedao_admin_generator_sqlite',$juhedao_admin_generator_sqlite);
    }


    private function handleConfigs(){
        $this->mergeConfigFrom(
            __DIR__ . '/config/main.php', 'juhedao-admin-generator.main'
        );
    }

    private function handleMigrations(){

    }

    private function handleAssets(){
        $this->publishes([
            __DIR__ . '/public' => base_path('/public/assets/juhedao/admin-generator')
        ]);
    }

    private function handleModels(){

    }

    private function handleViews(){
        $this->loadViewsFrom(__DIR__ . '/views', 'juhedao-admin-generator-views');
    }

    private function handleControllers(){

    }

    private function handleRoutes() {
        require __DIR__ . '/routes.php';
    }
}
