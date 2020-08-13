### Buer-B2B 框架

#### 安装步骤：
1. git clone https://github.com/buer2202/b2b.git
2. composer install
3. cp .env.example .env && php artisan key:generate
4. 配置数据库、redis、邮件相关参数。
5. php artisan migrate
6. php artisan db:seed
7. 可选（需做日结和自动通知时）：添加crontab计划任务：* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1

后台管理地址：http://网址/admin
<br>
默认管理员：administrator
<br>
默认密码：admin

#### 系统功能：
- 所有数据库自动迁移、自动填充。命令：php artisan migrate --seed
- WebSocket：集成Worderman的gatewayworder服务端、客户端。命令：php artisan gatewayworder start {--d}，可选参数为是否后台执行。
- API中间件/响应宏：buer.api中间件，接口数据aes解密替换到$request中。buerApi响应宏，对响应数据自动进行aes加密。
- 自动重复通知：通过仓库NotificationRepository管理，后台进程命令：php artisan notify:api。默认通过任务计划每分钟执行。

#### 业务功能
- 前台：注册，登录，注销，用户管理，财务管理。
- 后台：用户管理，前台RBAC，财务系统，财务报表，后台RBAC。
- 综合：资金日结，Excel导出、Excel异步导出js插件。
- 商品价格分组管理：可以按商品模型定义价格分组，配置文件：'price_group.goods_model'。
