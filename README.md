# tp5-blog
## 这原本是我的毕业设计项目，我把它迁移到Thinkphp5.0.10框架环境下

## 注意

### 如果集成开发环境不是phpstydy,那你就要要把public目录下的.htaccess文件的第七行换成

  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]，

### 然后配置数据库，在application目录下的database.php中

    // 数据库名
    'database'        => 'blog',
    // 用户名
    'username'        => 'blog',
    // 密码
    'password'        => 'blog1234',

### 修改成自己的。

### 接着，然后配置虚拟主机，把目录设置在public目录下，然后先注册一个管理员账号，管理员账号与application目录下的config.php的242行的值相同，可自行修改。

