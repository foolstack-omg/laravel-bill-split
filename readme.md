## 同游分账 - 服务器端

基于Laravel5.7 - Dingo Api - Jwt 开发的微信小程序服务器端接口程序

- [小程序客户端项目链接](https://github.com/654943305/bill-split-weapp)

## 功能点
- 微信小程序服务器端接口使用
- Dingo Api和Jwt的使用
- 使用Policy实现权限控制
- 使用Transformer控制Api返回结果

## 项目部署

1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan jwt:secret`
5. 配置 `.env` 文件中的微信小程序配置项以及数据库配置
6. 执行 `php artisan migrate`
7. `php artisan storage:link`

## 小程序码

<html>
<img width='200' src='https://bill-split.ergou.live/images/weapp_qrcode.jpg'/>
</html>

## 支持一下

您的支持是我前进最大的动力

<html>
<img width='200'  src='https://bill-split.ergou.live/images/receive_money.jpeg'/>
</html>

## 项目成员

- 技术 （刘晓峰 Stefan）
- UI设计 （优雅de兔子君）

## 联系我们

<html>
<img width='200'  src='https://bill-split.ergou.live/images/contact.jpeg'/>
</html>
