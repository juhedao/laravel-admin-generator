# Laravel Admin Generator

##说明

这不是一个正经的项目，是胖子利用富余时间一点一点累积出来的一个东西。所以很多东西没那么好用，很多代码乱七八糟，想一出是一出写出来的玩意就是这样的，所以别来找我喷啊。

另外胖子很忙，10月以前不会更新了。凑合自己改改用吧。

PS: 很多功能胖子还没测试，但是大概就是那样，错也错不到哪里去，坑不算太大！


##安装

在'composer.json'中间添加
```
{
    "require": {
        "juhedao/laravel-admin-generator": "dev-master"
    }
}
```
执行'composer install'


或者直接运行'composer require juhedao/laravel-admin-generator=dev-master'

建议直接运行这条命令，节约时间。


##配置

在'/config/app.php'的'providers'数组中添加
```
Juhedao\LaravelAdminGenerator\AdminGeneratorServiceProvider::class,
```

接着发布包的资源
```
php artisan vendor:publish
```

为主项目配置好数据库连接就可以使用了。

####注意：在项目正式发布后记得删除vendor下的以及'/public/assets'下的'juhedao'文件夹哦。


##使用

打开'http://yousite/admin/generator'就见到管理页面了

管理员账号是'admin'，密码是'admin888'.此外sqlite的管理密码也是'admin888'


###关于模板

layouts views controllers forms均支持从模板生成,除form外均支持多模板选择，form默认default,模板存储在'vendor/juhedao/laravel-admin-generator'下。支持子文件夹，建议使用 编号+点+名称 方式进行命名。

在从模板生成单一文件时，支持占位替换，批量生成不支持。


###关于数据库

在使用'生成Models','生成migrations','生成seeds','生成表单'这些功能前请先配置好主项目的数据库连接，胖子只测试了MYsql，其它坑请自己趟。

此外在'生成mirations'中还可以配置模板数据库连接，从其它数据库的数据表生成你需要的migration。


###关于列表中的复选框

如果是列表前的复选框代表此列不参与生成，列表中的复选框代表是否生成这个值。


###关于乱七八糟的代码

胖子比较懒(懒得创建文件和整理代码，不然也不会想着什么都UI化)，把所有功能写到了一个controller里了，还有好多废代码也没删除。之前还开一个class做数据库转migrations工作，后来干脆把大部分功能都写这个类了，作用和名称风马流不相及的。
还一个原因是时间少，而且前期没规划，直接开工中间也没返工，所以就成这样了。

还有好多想法没去实现，计划10月后推到重来，整理加添加新功能。


