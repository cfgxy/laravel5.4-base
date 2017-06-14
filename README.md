使用方法:
-------------
1. 从此项目 fork (派生) 新的项目
2. git clone 新项目到本地
3. cp .env.sample .env
4. 执行 git submodule init, git submodule update
5. 执行 ./artisan  key:generate; 为项目生成新的密钥
6. 修改本地 hosts; 添加  127.0.0.1   redis rabbitmq mysql docker redis-server
7. 准备好 docker 环境的前提下执行 ./container up -d;  ( https://www.daocloud.io/ 提供镜像加速 )
8. 等待环境初次创建(2分钟左右); 执行 ./artisan migrate 创建数据库结构
9. 执行 ./artisan db:seed 创建初始帐号 ( 用户名 admin@admin.cn; 密码 admin )
10. 访问 http://127.0.0.1/admin

前端开发指南:
-------------
1. 第三方库的引入参考 Composer 用法; ( http://www.phpcomposer.com/ 提供镜像加速 )
2. 第三方前端库的引入 参考 npm/yarn; ( https://npm.taobao.org/ 提供镜像加速 )
3. npm 环境装好后; 执行 npm install -f yarn 安装yarn
4. 在项目目录执行 yarn 安装前端开发依赖库
5. yarn run watch 可开启实时编译用于开发
6. 前端代码提交前执行 ./build-assets 打包产品版前端代码
7. 模块目录下 vue/app.js 和 vue/pages/*.js 以及 resources/assets/js/*.js 会被 webpack 分别打包成独立的 js 
8. 模块目录下 resources/assets/sass/*.js (非_前缀) 会被 webpack 分别打包成独立的 css 
9. 模块目录下 resources/dist/* 以及 根目录下的 resources/dist/*; 会被 webpack 软连接到 public/dist/ 下
10. 进行前端开发前; 设置 PhpStorm 的 JS 语言格式为 ES6, 不然会报各种前端语法检查错误。 或者在第一次打开项目前(打开过的先删除.idea 目录)执行 tar zxvfp idea.tar.gz; 大部分IDE配置已经做好，开发效率会很高

后端开发指南:
-------------
1. 参考 UserRole; 简单实现了枚举类型; 有一系列方法可用于枚举值的转换和列表
2. 参考 User; ExModel 实现了自动化repository, 可写同名 {Model}Repository 类进行逻辑扩充; BelongsToTenants表示使能 Landlord, 所有查询会自动带上隐含条件; 具体参考 github 上的 Landlord项目
3. plugins/guxy-common/helpers.php 中定义了一组常用函数，开发之前可浏览一下。 guxy_encrypt_id/guxy_decrypt_id 可实现 ID 加密方案。
4. API 的编写建议统一用 guxy_json_message; 代码中任意位置抛出的 AppException 如果没有捕获, 默认会被转写为 guxy_json_message 格式
5. 为使 Laravel 的代码更有条理性，减少开发中代码目录长距离跳转; 我们实现了类似 Symfony1 的模块化拆分方案; app/Features 下的子目录被称为模块，具有逻辑上的独立性
6. 可以使用 ./artisan   guxy:make:module 快速创建模块目录
7. 模块目录下的 routes 目录用于定义路由; 文件名会被作为 中间件组 加入到 route 配置中; 现有的中间件组有 web/api; 如果想实现更多组, 可以修改 app/Http/Kernel 实现
8. App\Http\Controllers\VueController@appPage 被设计为可以绑定到多个 routing 上; 根据 routing 中的地址加载对应模块下 resources/views/app.blade.php 模板
9. Admin模块下的 app.blade.php 模板加载了 adminlte 框架
10. 为支持 Repository; ide-helper的ide-helper:models命令被替换成了 guxy:ide-helper:models; 原来的命令还能用, 但是生成的内容不带 Repository 相关内容. 注意区分

运维指南:
-------------
1. 项目目录下 nginx.conf php.ini www.conf	 分别是 nginx配置 php配置 fpm 配置; 修改后执行 ./container restart 即可生效
2. 定时执行任务尽量用异步 dispatch Job 的方式进行; 系统级的定时任务可修改 docker/Dockerfile 添加
3. 上线运行后可通过修改 RELEASE_CODE 触发主动更新; 可修改 docker/app_check 进行进一步的修改
4. docker/app_refresh 将于每日凌晨2点定时执行; 如果有性能问题可从 docker/Dockerfile 中移除;
5. docker/app_init 用于初始化项目; 如有需要可进行修改; 修改后执行 ./container up -d --build 更新配置
6. docker/app_start 用于启动项目; 最初的代码演示了如何根据角色进行不同的启动流程; 如有需要可以修改
7. 修改 container 的 PROD_DOCKER_HOST 和 PROD_DOCKER_CERT_PATH; 配合阿里云容器服务; 执行 ./container --prod shell xxx 可直接管理线上容器
8. 修改 container push 段的地址; 配合阿里云私有容器镜像; 可实现本地打包, 远程部署 提高可维护性
9. docker/ssl/ 中的脚本用于自制证书链; 
