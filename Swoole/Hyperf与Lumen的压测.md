# Hyperf与Lumen的压测

## 目录
- [基础环境介绍](#基础环境介绍)
- [压测结果](#压测结果)
- [ab工具](#ab工具)
- [LNMP环境搭建](#LNMP环境搭建)
- [Lumen配置](#Lumen配置)
- [Lumen压测](#Lumen压测)
- [Hyperf配置](#Hyperf配置)
- [Hyperf压测](#Hyperf压测)
- [Lumen数据库操作压测](#Lumen数据库操作压测)
- [Hyperf数据库操作压测](#Hyperf数据库操作压测)

### 基础环境介绍
- CentOS 7.6 64位 Intel/Broadwell 1核 1G 20GB 
- PHP 7.3.12
- Nginx 1.16.1
- MySQL 5.7
- Lumen 5.7
- Swoole 4.4.12
- Hyperf 1.1.2

### ab工具
安装
```bash
yum -y install httpd-tools
```

### 压测结果
### 重要指标
Requests per second：吞吐率；  
Time per request：用户平均等待时间；
#### 压测参数
```bash
# 每秒并发100，总请求数1W
ab -c 100 -n 10000 http://127.0.0.1/category/1
```
#### 纯字符串输出
|     |  第一次   | 第二次  |
|  ----  | ----  | ----  |
| Lumen  | 吞吐率：888；用户平均等待时间：112； | 吞吐率：906；用户平均等待时间：110； |
| Hyperf  | 吞吐率：4430；用户平均等待时间：22； | 吞吐率：4380；用户平均等待时间：22； |
#### 一次数据库查询
|     |  第一次   | 第二次  |
|  ----  | ----  | ----  |
| Lumen  | 吞吐率：326；用户平均等待时间：305； | 吞吐率：328；用户平均等待时间：304； |
| Hyperf  | 吞吐率：1393；用户平均等待时间：71； | 吞吐率：1366；用户平均等待时间：73； |

### LNMP环境搭建

基础环境
```bash
# 安装基础软件
yum -y install vim wget

# 应用目录
mkdir -p /data/www && chmod -R 777 /data/www
```

PHP相关
```bash
# 安装epel源
yum install -y epel-release &&\
	rpm -ivh https://mirrors.tuna.tsinghua.edu.cn/remi/enterprise/remi-release-7.rpm
	
# 安装PHP及其扩展
yum install -y --enablerepo=remi --enablerepo=remi-php73 \
    php \
    php-opcache \
    php-devel \
    php-mbstring \
    php-xml \
    php-zip \
    php-cli \
    php-fpm \
    php-mcrypt \
    php-mysql \
    php-pdo \
    php-curl \
    php-gd \
    php-mysqld \
    php-bcmath \
    php-redis \
    php-process \
    openssh-server \
    gcc \
    gcc-c++ \
    make \
    unzip &&\
    mkdir /run/php-fpm/
    
# 安装Composer
curl -sSL https://getcomposer.org/installer | php &&\
    mv composer.phar /usr/local/bin/composer &&\
    composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```

Nginx相关
```bash
cd /usr/src &&\ 
    wget http://nginx.org/packages/centos/7/noarch/RPMS/nginx-release-centos-7-0.el7.ngx.noarch.rpm &&\
    rpm -ivh nginx-release-centos-7-0.el7.ngx.noarch.rpm &&\
    yum -y install nginx
```

MySQL相关
```bash
cd /usr/src &&\
    wget http://repo.mysql.com/mysql57-community-release-el7-8.noarch.rpm &&\
    rpm -ivh mysql57-community-release-el7-8.noarch.rpm &&\
    yum -y install mysql-community-server
    
# 启动服务
service mysqld start
# 查看密码
cat /var/log/mysqld.log | grep 'temporary password'
# 登录并修改密码
mysql> set password = password('WYX*wyx123');
# 开放MySQL远程连接(%表示允许任何主机连接)
mysql> use mysql;
mysql> select host,user from user;
mysql> update user set host = '%' where host = 'localhost' and user = 'root';
mysql> flush privileges;

# 建库建表
create database app;

CREATE TABLE `t_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `category_name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `category_remark` varchar(32) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 -1:禁用;1:启用;',
  `mtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  `ctime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='测试分类表';

# 插入1条测试数据
insert into t_category (category_name,category_remark) values ('测试分类1', '测试分类1的描述');
```

### Lumen配置
安装
```bash
# 进入www目录
cd /data/www

# 通过composer安装
composer create-project laravel/lumen lumen-app "5.7.*"
```
Nginx配置
```bash
# conf文件配置
mv /etc/nginx/conf.d/default.conf /etc/nginx/conf.d/lumen-app.conf
vim /etc/nginx/conf.d/lumen-app.conf
```
lumen-app.conf内容
```bash
server {
    listen       80;
    server_name  lumen-app.com;

    root /data/www/lumen-app/public;
    index index.html index.htm index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        try_files $uri =404;
    }
}
```
启动
```bash
# 启动PHP-FPM
/usr/sbin/php-fpm -c /etc/php.ini -y /etc/php-fpm.conf

# 启动Nginx
service nginx restart
```
浏览器访问：http://106.75.117.140/
```json
Lumen (5.7.8) (Laravel Components 5.7.*)
```

### Lumen压测
压测
```bash
ab -c 100 -n 10000 http://127.0.0.1/
```
压测结果
```bash
# 第一次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /
Document Length:        40 bytes

Concurrency Level:      100
Time taken for tests:   11.259 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      2750000 bytes
HTML transferred:       400000 bytes
Requests per second:    888.16 [#/sec] (mean)
Time per request:       112.592 [ms] (mean)
Time per request:       1.126 [ms] (mean, across all concurrent requests)
Transfer rate:          238.52 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      0       2
Processing:     6  112  17.6    107     190
Waiting:        3  111  17.6    107     189
Total:          6  112  17.5    108     190

Percentage of the requests served within a certain time (ms)
  50%    108
  66%    116
  75%    122
  80%    124
  90%    136
  95%    146
  98%    161
  99%    164
 100%    190 (longest request)

# 第二次 
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /
Document Length:        40 bytes

Concurrency Level:      100
Time taken for tests:   11.033 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      2750000 bytes
HTML transferred:       400000 bytes
Requests per second:    906.35 [#/sec] (mean)
Time per request:       110.332 [ms] (mean)
Time per request:       1.103 [ms] (mean, across all concurrent requests)
Transfer rate:          243.40 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    1   0.3      1       3
Processing:     6  109  13.8    108     192
Waiting:        3  108  13.9    108     192
Total:          6  110  13.7    109     193

Percentage of the requests served within a certain time (ms)
  50%    109
  66%    110
  75%    112
  80%    117
  90%    125
  95%    137
  98%    151
  99%    154
 100%    193 (longest request)
```

### Hyperf配置
安装Swoole4.4+
```bash
wget https://github.com/swoole/swoole-src/archive/v4.4.12.tar.gz &&\
	tar -zxvf v4.4.12.tar.gz &&\
	cd swoole-src-4.4.12 &&\
	phpize &&\
	./configure &&\
	make && make install &&\
	sed -i '$a \\n[swoole]\nextension=swoole.so\n' /etc/php.ini &&\
	cd ../ && rm -rf v4.4.12.tar.gz swoole-src-4.4.12
```
关闭Swoole短名
```bash
vim /etc/php.ini
# 新增一行
swoole.use_shortname = 'Off'
# 查看swoole扩展信息
$ php --ri swoole

swoole

Swoole => enabled
Author => Swoole Team <team@swoole.com>
Version => 4.4.12
Built => Dec 14 2019 19:42:51
coroutine => enabled
epoll => enabled
eventfd => enabled
signalfd => enabled
cpu_affinity => enabled
spinlock => enabled
rwlock => enabled
http2 => enabled
pcre => enabled
zlib => 1.2.7
mutex_timedlock => enabled
pthread_barrier => enabled
futex => enabled
async_redis => enabled

Directive => Local Value => Master Value
swoole.display_errors => On => On
swoole.enable_coroutine => On => On
swoole.enable_library => On => On
swoole.enable_preemptive_scheduler => Off => Off
swoole.unixsock_buffer_size => 8388608 => 8388608
swoole.use_shortname => Off => Off
```

安装Hyperf
```bash
# 进入www目录
cd /data/www

# 通过composer安装
composer create-project hyperf/hyperf-skeleton hyperf-app
```

关闭PHP-FPM
```bash
kill -INT `cat /run/php-fpm/php-fpm.pid`
```

Nginx配置
```bash
# conf文件配置
touch /etc/nginx/conf.d/hyperf-app.conf
vim /etc/nginx/conf.d/hyperf-app.conf
```
hyperf-app.conf内容
```bash
upstream hyperf {
    # 至少需要一个 Hyperf 节点，多个配置多行
    server 127.0.0.1:9501;
}

server {
    listen 80; 
    server_name hyperf-app.com;

    location / {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点  
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 执行代理访问真实服务器
        proxy_pass http://hyperf;
    }
}
```
启动
```bash
# 启动Hyperf服务
cd /data/www/hyperf-app && php bin/hyperf.php start

# 重启Nginx
service nginx restart
```
浏览器访问：http://106.75.117.140/
```json
{
  "method": "GET",
  "message": "Hello Hyperf."
}
```

### Hyperf压测
压测
```bash
ab -c 100 -n 10000 http://127.0.0.1/
```
压测结果
```bash
# 第一次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /
Document Length:        42 bytes

Concurrency Level:      100
Time taken for tests:   2.257 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      1910000 bytes
HTML transferred:       420000 bytes
Requests per second:    4430.87 [#/sec] (mean)
Time per request:       22.569 [ms] (mean)
Time per request:       0.226 [ms] (mean, across all concurrent requests)
Transfer rate:          826.46 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    2   0.6      2       4
Processing:     4   21   1.0     21      24
Waiting:        2   19   0.8     19      22
Total:          4   23   0.9     22      25
WARNING: The median and mean for the total time are not within a normal deviation
        These results are probably not that reliable.

Percentage of the requests served within a certain time (ms)
  50%     22
  66%     23
  75%     23
  80%     23
  90%     24
  95%     24
  98%     24
  99%     25
 100%     25 (longest request)

# 第二次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /
Document Length:        42 bytes

Concurrency Level:      100
Time taken for tests:   2.283 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      1910000 bytes
HTML transferred:       420000 bytes
Requests per second:    4380.00 [#/sec] (mean)
Time per request:       22.831 [ms] (mean)
Time per request:       0.228 [ms] (mean, across all concurrent requests)
Transfer rate:          816.97 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    2   0.5      2       4
Processing:     5   21   1.4     21      31
Waiting:        2   19   1.2     19      29
Total:          5   23   1.3     22      32

Percentage of the requests served within a certain time (ms)
  50%     22
  66%     23
  75%     23
  80%     23
  90%     24
  95%     25
  98%     25
  99%     32
 100%     32 (longest request)
```


### Lumen数据库操作压测
配置
```bash
# .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=root
DB_PASSWORD=WYX*wyx123

# routes/web.php
$router->get('category/{id}', 'CategoryController@detail');

# app/Http/Controllers/CategoryController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function detail($id)
    {
        $sql = 'select * from t_category where id = ' . $id;
        // 需要在bootstrap/app.php中打开$app->withFacades();
        return DB::select($sql);
    }
}
```
浏览器访问：http://106.75.117.140/category/1
```json
[
    {
        "id": 1,
        "category_name": "测试分类1",
        "category_remark": "测试分类1的描述",
        "status": 1,
        "mtime": "2019-12-14 21:46:40",
        "ctime": "2019-12-14 21:46:40"
    }
]
```
压测
```bash
ab -c 100 -n 10000 http://127.0.0.1/category/1
```
压测结果
```bash
# 第一次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /category/1
Document Length:        189 bytes

Concurrency Level:      100
Time taken for tests:   30.597 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      4160000 bytes
HTML transferred:       1890000 bytes
Requests per second:    326.83 [#/sec] (mean)
Time per request:       305.968 [ms] (mean)
Time per request:       3.060 [ms] (mean, across all concurrent requests)
Transfer rate:          132.78 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      0       3
Processing:    11  305 110.7    284     728
Waiting:        9  304 110.6    283     728
Total:         12  305 110.6    284     729

Percentage of the requests served within a certain time (ms)
  50%    284
  66%    319
  75%    373
  80%    422
  90%    480
  95%    517
  98%    553
  99%    581
 100%    729 (longest request)

# 第二次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /category/1
Document Length:        189 bytes

Concurrency Level:      100
Time taken for tests:   30.408 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      4160000 bytes
HTML transferred:       1890000 bytes
Requests per second:    328.86 [#/sec] (mean)
Time per request:       304.080 [ms] (mean)
Time per request:       3.041 [ms] (mean, across all concurrent requests)
Transfer rate:          133.60 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      0       3
Processing:    13  303 123.5    249     625
Waiting:       10  302 123.4    249     624
Total:         13  303 123.5    249     625

Percentage of the requests served within a certain time (ms)
  50%    249
  66%    314
  75%    428
  80%    449
  90%    497
  95%    527
  98%    562
  99%    587
 100%    625 (longest request)
```

### Hyperf数据库操作压测
```bash
# 安装组件
composer require hyperf/db-connection

# .env
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=root
DB_PASSWORD=WYX*wyx123
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_PREFIX=

# config/routes.php
Router::get('/category/{id}', 'App\Controller\CategoryController@detail');

# app/Controller/CategoryController.php
<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\DbConnection\Db;

class CategoryController extends AbstractController
{
    public function detail(int $id)
    {
        $res = Db::table('t_category')->where('id', $id)->get();
        return $res;
    }
}
```
浏览器访问：http://106.75.117.140/category/1
```json
[
    {
        "id": 1,
        "category_name": "测试分类1",
        "category_remark": "测试分类1的描述",
        "status": 1,
        "mtime": "2019-12-14 21:46:40",
        "ctime": "2019-12-14 21:46:40"
    }
]
```

压测
```bash
ab -c 100 -n 10000 http://127.0.0.1/category/1
```
压测结果
```bash
# 第一次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /category/1
Document Length:        156 bytes

Concurrency Level:      100
Time taken for tests:   7.174 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      3060000 bytes
HTML transferred:       1560000 bytes
Requests per second:    1393.98 [#/sec] (mean)
Time per request:       71.737 [ms] (mean)
Time per request:       0.717 [ms] (mean, across all concurrent requests)
Transfer rate:          416.56 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.2      0       3
Processing:    20   71   3.6     71     119
Waiting:       18   71   3.6     71     119
Total:         21   71   3.6     71     120

Percentage of the requests served within a certain time (ms)
  50%     71
  66%     72
  75%     72
  80%     72
  90%     74
  95%     75
  98%     77
  99%     79
 100%    120 (longest request)

# 第二次
Server Software:        nginx/1.16.1
Server Hostname:        127.0.0.1
Server Port:            80

Document Path:          /category/1
Document Length:        156 bytes

Concurrency Level:      100
Time taken for tests:   7.318 seconds
Complete requests:      10000
Failed requests:        0
Write errors:           0
Total transferred:      3060000 bytes
HTML transferred:       1560000 bytes
Requests per second:    1366.54 [#/sec] (mean)
Time per request:       73.178 [ms] (mean)
Time per request:       0.732 [ms] (mean, across all concurrent requests)
Transfer rate:          408.36 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.2      0       3
Processing:    13   73   5.4     71     103
Waiting:       10   73   5.4     71     103
Total:         13   73   5.3     71     104

Percentage of the requests served within a certain time (ms)
  50%     71
  66%     74
  75%     75
  80%     76
  90%     80
  95%     81
  98%     85
  99%     89
 100%    104 (longest request)
```