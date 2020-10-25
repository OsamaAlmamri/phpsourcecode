# 角色权限控制系统
YunCMS实现了简单的RBAC权限控制系统。

## RBAC在YunCMS的实现步聚
- 判断是否登录
- 判断请求是否需要验证权限。
- 获取当前用户的角色信息。
- 验证当前角色是否可以访问当前操作。

## `authorization.php`文件
`/app/extra/authorization.php`文件定义了以下规则：
- 后台管理系统的菜单结构。
- RBAC权限管理系统的授权目录。

需要登录才可以访问的控制器是需要权限验证的,只有通过权限验证才可以继续访问。

在`app\admin\controller\AdminBaseController.php`中使用了`$allow_actions`用来设置控制器的操作是否忽略登录验证。
