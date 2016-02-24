## WeixinGate微系统
###微信开发者需要解决哪些问题？
1. 微信授权过程中的跳转问题（微信授权只能向一个确定前缀的域名进行跳转，来传递code），故多系统中同时需要同一个微信公众号授权登陆时产生问题。
2. 微信定时获取accss_token问题，微信机制每隔一定时间（通常为2个小时）会要求程序刷新token，我们需要维护access token的定时刷新逻辑。
3. 每次获取access_token之后，微信前端开发的js-ticket将会发生变化，此时将要重新计算js-ticket，并提供相应的controller来获取access token和ticket。

###WeixinGate实现功能
1. WeixinGate部署的一个指定下之后，为所有应用提供code凭据跳转服务。
2. WeixinGate会定时刷新公众号的access_token并计算ticket，并提供相应的接口供各个不同的项目获取。
3. WeixinGate可以配置多个公众号，作为一个繁忙的转发维护系统。

###WeixinGate为什么要用Lumen来做
1. 快

###WeixinGate的不足之处
1. 单点故障，这个可以通过分布式集群来解决
2. 安全性，理论上讲，只要公众号相应配置不在http协议中传输，即便获取相应的code或者access-token对系统的安全性影响不是很大（对此并不承诺）

###安装配置
1. 环境配置 https://lumen.laravel.com/docs/5.2/installation
2. 数据库安装 php artisan migrate
3. 计划任务
    Cron entry you need to add to your server:
    【* * * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1】



### License

The framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
### contact
bigtomato dxywx@sina.com