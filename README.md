# Laravel Magic Gui

##说明

这不是一个正经的项目，是胖子利用富余时间一点一点累积出来的一个东西。所以很多东西没那么好用，很多代码乱七八糟，想一出是一出写出来的玩意就是这样的，所以别来找我喷啊。

另外胖子很忙，10月以前不会更新了。凑合自己改改用吧。

PS: 很多功能胖子还没测试，但是大概就是那样，错也错不到哪里去，坑不算太大！


##安装

在'composer.json'中间添加"juhedao/laravel-magic-gui": "dev-master",执行
···
composer install
···

或者直接运行
···
composer require juhedao/laravel-magic-gui
···


##配置

在'/config/app.php'的'providers'数组中添加
···
Juhedao\LaravelAdminGenerator\AdminGeneratorServiceProvider::class,
···

接着发布包的资源
···
php artisan vendor:publish
···

####注意：在项目正式发布后记得删除vendor下的以及'/public/assets'下的'juhedao'文件夹哦。


##使用

打开'http://yousite/admin/generator'就见到管理页面了

管理员账号是'admin'，密码是'admin888'.此外sqlite的管理密码也是'admin888'


###命令

命令在首页，可以按照模板自行添加命令模板。之后每次填空就可以了。话说忘记做常用命令保存的功能了。


###创建Views



