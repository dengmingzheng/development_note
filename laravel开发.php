<?php

(laravel学习篇)

一:安装 Laravel 之后，你要将 Web 服务器的根目录指向 public 目录。该目录下的 index.php 文件将作为所有进入应用程序的 HTTP 请求的前端控制器。

二:目录权限
   安装完 Laravel 后，你可能需要给这两个文件配置读写权限：storage 目录和 bootstrap/cache 目录应该允许 Web 服务器写入，否则 Laravel 将无法运行。如果你使用的是 Homestead 虚拟机，这些权限已经为你设置好了。

三:应用密钥  
   重新生成秘钥:php artisan key:generate 

四:确定当前环境
   $environment = App::environment();   
   if (App::environment('local')) {
    // 环境为 local
   }
   if (App::environment(['local', 'staging'])) {
    // 环境为 local 或 staging
   }

五:访问配置值
   $value = config('app.timezone');

六:设置配置值
   config(['app.timezone' => 'America/Chicago']);   

七:维护模式
   1:php artisan down
   2:php artisan down --message="Upgrading Database" --retry=60
   3:你可以通过修改 resources/views/errors/503.blade.php 模板文件来自定义默认维护模式模板
   4:关闭维护模式:php artisan up
   5:维护模式和队列(当应用程序处于维护模式时，不会处理 队列任务。而这些任务会在应用程序退出维护模式后再继续处理);
   6:维护模式的替代方案(维护模式会导致应用程序有数秒的停机（不响应）时间，因此你可以考虑使用像 Envoyer 这样的替代方案，以便与 Laravel 完成零停机时间部署。)

八:文件夹结构
   1：app目录(app目录包含应用程序的核心代码)
   2:Bootstrap目录(bootstrap 目录包含引导框架并配置自动加载的文件。该目录还包含了一个 cache 目录，存放着框架生成的用来提升性能的文件，比如路由和服务缓存文件)   
   3:Config目录(config目录，顾名思义，包含应用程序所有的配置文件)
   4:Database目录(目录包含数据填充和迁移文件)
   5:Public目录(目录包含了入口文件 index.php，它是进入应用程序的所有请求的入口点。此目录还包含了一些你的资源文件（如图片、JavaScript 和 CSS）)
   6:Resources目录(目录包含了视图和未编译的资源文件（如 LESS、SASS 或 JavaScript）。此目录还包含你所有的语言文件)
   7:Routes目录(目录包含了应用的所有路由定义，Laravel 默认包含了几个路由文件：web.php、api.php、 console.php 和 channels.php)
   8:Storage目录(storage目录包含编译的 Blade 模板、基于文件的会话和文件缓存、以及框架生成的其他文件。这个目录被细分成 app、framework 和 logs 三个子目录)
   9:Tests目录(目录包含自动化测试文件)
   10:Vendor目录(目录包含了你的Composer依赖包)
   11:Console目录(目录包含了所有自定义的Artisan命令。这些命令可以通过make:command来生成。这个目录还包含了控制台内核，可以用来注册你的自定义Artisan命令和你定义的计划任务 的地方)
   12:Events目录(目录默认是不存在的，它会在你运行Artisan命令event:generate或event:make时生成。Events目录存放了事件类)
   13:Exceptions目录(目录包含了应用的异常处理器，也是应用跑出异常的好地方)
   14:Http目录(目录包含了控制器、中间件和表单请求)
   15:Jobs目录(目录默认是不存在的，它会在你运行Artisan命令make:job时生成)
   16:Listeners目录(目录默认是不存在的，它会在你运行 Artisan 命令 event:generate 或 make:listenr 时生成。Listeners 目录包含了用来处理 事件 的类。事件监听器接收事件实例并执行响应该事件被触发的逻辑)
   17:Mail目录(目录默认不存在，它会在你运行 Artisan 命令 make:mail 时生成。Mail 目录包含应用所有的邮件发送类。邮件对象允许你将构建邮件的逻辑封装在可以使用 Mail::send 方法来发送邮件的地方)
   18:Notifications目录(目录默认不存在，它会在你运行 Artisan 命令 make:notification 时生成。Notifications 目录包含应用发送的所有「事务性」通知，比如关于在应用中发生的事件的简单通知。Laravel 的通知功能抽象了通知发送，可以通过各种驱动（例如邮件、Slack、短信）发送通知，或是存储在数据库中)
   19:Policies目录(目录默认不存在，它会通过运行 Artisan 命令 make:policy 来创建。Policies 目录包含了应用的授权策略类。策略可以用来决定一个用户是否有权限去操作指定资源)
   20:Providers目录(目录包含了应用的所有 服务提供器。服务提供器通过在服务容器中绑定服务、注册事件、以及执行其他任务来为即将到来的请求做准备来启动应用)
   21:Rules目录(目录默认不存在，它会在运行 Artisan 命令 make:rule 命令时被创建。Rules 目录包含应用自定义验证规则对象。这些规则意在将复杂的验证逻辑封装在一个简单的对象中。更多详情可以查看 验证文档)


一：本地邮件服务器配置
    1:访问链接 http://shop.test:8025 
    2:服务器配置
    MAIL_DRIVER=smtp
		MAIL_HOST=127.0.0.1
		MAIL_PORT=1025
		MAIL_USERNAME=null
		MAIL_PASSWORD=null
		MAIL_ENCRYPTION=null

二: 数据库迁移
    
    1:php artisan make:model User -fm (-fm 参数代表同时生成 factory 工厂文件和 migration 数据库迁移文件)
    2:php artisan make:seeder UserSeeder (生成数据填充文件)
    3:php artisan db:seed  php artisan db:seed --class=UsersTableSeeder  php artisan migrate:refresh --seed

三：重命名工厂文件之后需要执行 composer dumpautoload，否则会找不到对应的工厂文件

四：factory 工厂文件会使用 faker 来自动生成字段的内容，默认情况下是英文，我们可以修改成中文：config/app.php->'faker_locale' => 'zh_CN'

五：启动队列处理器 php artisan queue:work

六：load()和with()的区别
    with:预加载方法,是在 ORM 查询构造器上调用
    load:延迟预加载方法,是在已经查询出来的模型上调用



laravel开发必装插件

三：卸载composer安装的扩展包            
    composer remove 包名

四：清除路由缓存:php artisan route:cache    

五：清除视图缓存:php artisan view:clear 

六：清除配置缓存:php artisan config:clear 

七：composer加速器:composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/


一:样式调整(Laravel Mix)
   Laravel Mix 一款前端任务自动化管理工具，使用了工作流的模式对制定好的任务依次执行。Mix 提供了简洁流畅的 API，让你能够为你的 Laravel 应用定义 Webpack 编译任务。Mix 支持许多常见的 CSS 与 JavaScript 预处理器，通过简单的调用，你可以轻松地管理前端资源。
   使用 Mix 很简单，首先你需要使用以下命令安装 npm 依赖即可。我们将使用 Yarn 来安装依赖，在这之前，因为国内的网络原因，我们还需为 Yarn 配置安装加速：
   0：npm config set registry=https://registry.npm.taobao.org
   1:yarn config set registry https://registry.npm.taobao.org
   2:使用 Yarn 安装依赖:yarn install
   3:安装成功后，运行以下命令即可：npm run watch-poll

   watch-poll 会在你的终端里持续运行，监控 resources 文件夹下的资源文件是否有发生改变。在 watch-poll 命令运行的情况下，一旦资源文件发生变化，Webpack 会自动重新编译


二:验证码扩展包
   1:使用 Composer 安装：composer require "mews/captcha:~2.0"
   2:运行以下命令生成配置文件 config/captcha.php： php artisan vendor:publish --provider='Mews\Captcha\CaptchaServiceProvider' 

三:图片裁切扩展包
   1:Composer安装:composer require intervention/image
   2:配置信息:php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"  

四:代码生成器
   1:通过 Composer 安装:composer require "summerblue/generator:~0.5" --dev       

五:Debugbar
   1:使用 Composer 安装： composer require "barryvdh/laravel-debugbar:~3.1" --dev
   2:生成配置文件，存放位置 config/debugbar.php(php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider")
   3:打开 config/debugbar.php，将 enabled 的值设置为：('enabled' => env('APP_DEBUG', false))   

六：Horizon
   1：composer require "laravel/horizon:~1.0"
   2：php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"
   3：至此安装完毕，浏览器打开 http://larabbs.test/horizon 访问控制台：
   4：Horizon 是一个监控程序，需要常驻运行，我们可以通过以下命令启动：php artisan horizon，安装了 Horizon 以后，我们将使用 horizon 命令来启动队列系统和任务监控，无需使用queue:listen   
   5：线上部署须知
      在开发环境中，我们为了测试方便，直接在命令行里调用 artisan horizon 进行队列监控。然而在生产环境中，我们需要配置一个进程管理工具来监控 artisan horizon 命令的执行，以便在其意外退出时自动重启。当服务器部署新代码时，需要终止当前 Horizon 主进程，然后通过进程管理工具来重启，从而使用最新的代码。

      简而言之，生产环境下使用队列需要注意以下两个问题：

      1：使用 Supervisor 进程工具进行管理，配置和使用请参照 文档 进行配置；
      2：每一次部署代码时，需 artisan horizon:terminate 然后再 artisan horizon 重新加载代码



七：使用swoole加速laravel

    1:使用composer安装swoole扩展
      composer require swooletw/laravel-swoole

    2:安装swoole
      1：pecl install swoole
      2：验证是否安装成功
         php --ri swoole
      3：查看版本
         php --ri swoole | grep Version   

    3:开启swoole扩展
      1: php -i | grep php.ini //查找php.ini文件位置
        
      2: sudo echo "extension=swoole.so" > php.ini //开启swoole扩展
          //sudo echo "extension=swoole.so" > /etc/php/7.3/cli/php.ini
      3: php -m | grep swoole //查看swoole扩展是否生效

    4: 在 config/app.php 中添加服务提供者 
       'providers' => [
              SwooleTW\Http\LaravelServiceProvider::class,
        ],

    5：启动 Swoole HTTP 服务
       php artisan swoole:http start  


八：安装配置 LaravelS
    1：composer require hhxsv5/laravel-s
    2：php artisan laravels publish
    3：php bin/laravels start //启动 LaravelS
    命令:
        start:启动LaravelS
        strp:停止LaravelS
        restart:重启LaravelS
        reload: 平滑重启所有Task/Worker进程,这些进程内包含了你的业务代码，不会重启Master/Manger/Timer/Custom进程
        info:显示组件的版本信息
        help:显示帮助信息  
         ------------------------Bug解决----------------------------

九：中文语言包
    1：composer require "overtrue/laravel-lang:~3.0"
    2：完成上面的操作后，将项目文件 config/app.php 中的下一行
       Illuminate\Translation\TranslationServiceProvider::class 替换为 Overtrue\LaravelLang\TranslationServiceProvider::class

十：短信发送组件
   1：composer require "overtrue/easy-sms"

十一:Composer 抱怨内存不够
    如出现 Composer 抱怨内存不够的问题，请执行 composer self-update 升级到 2.0 
      
1:Migration not found: 2019_07_25_213854_create_admins_table
  原因:手动删除数据表迁移文件后再次创建报错
  解决:执行命令--composer dump-autoload

2：Ubuntu 18.04 使用root进行ssh登录
  1:安装 open ssh:  sudo apt-get install openssh-server
  2：sudo vim /etc/ssh/sshd_config
  3:找到#PermitRootLogin，并将其改为 PermitRootLogin yes
  4:重启ssh服务 sudo service ssh restart

3：Composer内存限制错误
  1：memory_limit = -1


cd ~/Homestead && vagrant provision && vagrant reload  