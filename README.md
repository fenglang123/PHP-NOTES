<h1 align="center"> 
    PHP-NOTES 
</h1>
<h3 align="center"> 
    以 markdown 的形式记录平时遇到的知识点，查漏补缺 
</h3>
<p align="center"> 
    Linux、计算机网络、数据结构、算法、PHP、MySQL、设计模式、Redis...
</p>

### 目录

- Linux
  - [inode](Linux/inode.md)
  - [软链接和硬链接](Linux/软链接和硬链接.md)  
  - [LinuxIO模型](Linux/LinuxIO模型.md)
  - [select、poll、epoll](Linux/select、poll、epoll.md)
  - [并发和并行的区别](Linux/并发和并行的区别.md)
  - [进程和线程的区别](Linux/进程和线程的区别.md)
  - [多CPU、多核、多进程、多线程、并发、并行](Linux/多CPU、多核、多进程、多线程、并发、并行.md)
  - [协程](Linux/协程.md)
  - [孤儿进程和僵尸进程](Linux/孤儿进程和僵尸进程.md)
  - [Linux基本命令](Linux/Linux基本命令.md)
  - [文本处理工具sed](Linux/文本处理工具sed.md)
  - [文件统计wc](Linux/文件统计wc.md)
  - [定时任务crontab](Linux/定时任务crontab.md)
  - [文本编辑器Vim](Linux/文本编辑器Vim.md)
  - [如何平滑重启PHP-FPM](Linux/如何平滑重启PHP-FPM.md)
  - [Linux下如何查看端口](Linux/Linux下如何查看端口.md)
  - [CentOS7搭建samba实现与Win共享目录](Linux/CentOS7搭建samba实现与Win共享目录.md)
  - [awk](Linux/awk.md)
  - [找出文本中含有'linux'的行,如何统计共有多少行](Linux/找出文本中含有'linux'的行,如何统计共有多少行.md)
  - [free命令](Linux/free命令.md)
  - [top命令](Linux/top命令.md)
  - [curl](Linux/curl.md)

- 计算机网络
  - [计算机网络体系结构](计算机网络/计算机网络体系结构.md)
  - [TCP三次握手和四次挥手](计算机网络/TCP三次握手和四次挥手.md)
  - [TCP和UDP的区别](计算机网络/TCP和UDP的区别.md)
  - [HTTP概述](计算机网络/HTTP概述.md)
  - [HTTP和HTTPS的区别](计算机网络/HTTP和HTTPS的区别.md)
  - [一次完整的HTTP请求过程](计算机网络/一次完整的HTTP请求过程.md)
  - [GET和POST请求方式的区别](计算机网络/GET和POST请求方式的区别.md)
  - [常见的HTTP状态码](计算机网络/常见的HTTP状态码.md)
  - [会话技术(Cookie和Session)](计算机网络/会话技术(Cookie和Session).md)
  
- 数据结构
  - [数据结构的概念和分类](数据结构/数据结构的概念和分类.md)
  - 树
    - [树的概念、基础术语、表示方法](数据结构/树的概念、基础术语、表示方法.md)
    - 二叉树
      - [二叉树的概念、分类和性质](数据结构/二叉树的概念、分类和性质.md)
  - [堆结构与时间复杂度分析](数据结构/堆结构与时间复杂度分析.md)
  - [栈](数据结构/栈.md)
  - [队列](数据结构/队列.md)
  - 链表
    - [单向链表](数据结构/单向链表.md)
    - [双向链表](数据结构/双向链表.md)
    - [循环链表](数据结构/循环链表.md)
  
- 算法
  - [认识时间复杂度](算法/认识时间复杂度.md)
  - [对数器](算法/对数器.md)
  - [递归行为的实质和递归行为时间复杂度的计算](算法/递归行为的实质和递归行为时间复杂度的计算.md)
  - 排序
    - 基于比较的排序
      - [冒泡排序](算法/冒泡排序.md)
      - [选择排序](算法/选择排序.md)
      - [插入排序](算法/插入排序.md)
      - [归并排序](算法/归并排序.md)
      - [快速排序](算法/快速排序.md)
      - [堆排序](算法/堆排序.md)
    - [非基于比较的排序](算法/非基于比较的排序.md)
      - [计数排序](算法/计数排序.md)
  
  - [排序算法的稳定性及其汇总](算法/排序算法的稳定性及其汇总.md)
  - [Java中的比较器之Comparator](算法/Java中的比较器之Comparator.md)
    
  - [小和问题](算法/小和问题.md)
  - [荷兰国旗问题](算法/荷兰国旗问题.md)
  - [相邻两数最大差值问题](算法/相邻两数最大差值问题.md)
  - [实现特殊的栈,返回栈中最小元素](算法/实现特殊的栈,返回栈中最小元素.md)
  - [两个队列实现一个栈](算法/两个队列实现一个栈.md)
  - [两个栈实现一个队列](算法/两个栈实现一个队列.md)
  - [猫狗队列](算法/猫狗队列.md)
  - [转圈打印矩阵](算法/转圈打印矩阵.md)
  - [旋转正方形矩阵](算法/旋转正方形矩阵.md)
  - [求n以内的质数](算法/求n以内的质数.md)
  - [求两个有序数组的公共元素](算法/求两个有序数组的公共元素.md)
  - [猴子选大王](算法/猴子选大王.md)

- PHP
  - [PHP数据类型](PHP/PHP数据类型.md)
  - [CGI、FastCGI、PHP-FPM、PHP-CGI](PHP/关于CGI、FastCGI、PHP-FPM、PHP-CGI.md)
  - [Trait](PHP/Trait.md)
  - [yield](PHP/yield.md)
  - [并发问题的解决方案](PHP/并发问题的解决方案.md)
  - PHP多进程
    - [PHP多进程初探-创建子进程](PHP/PHP多进程初探-创建子进程.md)
    - [PHP进程间通信](PHP/PHP进程间通信.md)
      - [消息队列](PHP/消息队列.md)
      - [信号量和共享内存](PHP/信号量和共享内存.md)
      - [管道](PHP/管道.md)
  - PHP内核
    - [PHP5中的zval](PHP/PHP5中的zval.md)
    - [PHP7中的zval](PHP/PHP7中的zval.md)
    - [PHP7中数组的实现原理](PHP/PHP7中数组的实现原理.md)
    - [PHP弱类型是如何实现的](PHP/PHP弱类型是如何实现的.md)
    - [PHP是如何实现二进制安全的](PHP/PHP是如何实现二进制安全的.md)
    - [PHP7中的zend_reference](PHP/PHP7中的zend_reference.md)
  - PHP垃圾回收
    - [PHP5引用计数基本知识](PHP/PHP5引用计数基本知识.md)
    - [PHP5.3中的垃圾回收机制](PHP/PHP5.3中的垃圾回收机制.md)
    - [PHP7中的垃圾回收机制](PHP/PHP7中的垃圾回收机制.md)
  - 常见面试题
    - [echo,print,print_r的区别](PHP/echo,print,print_r的区别.md)
    - [isset和empty之间的区别是什么](PHP/isset和empty之间的区别是什么.md)
    - [isset和array_key_exists之间的区别](PHP/isset和array_key_exists之间的区别.md)
    - [字符串截取的函数有哪些](PHP/字符串截取的函数有哪些.md)
    - [error_reporting函数的作用是什么](PHP/error_reporting函数的作用是什么.md)
    - [考察单引号和双引号的区别](PHP/考察单引号和双引号的区别.md)
    - [描述一下常见的关于读取文件内容的PHP函数](PHP/描述一下常见的关于读取文件内容的PHP函数.md)
    - [如何获取客户端IP和服务端IP](PHP/如何获取客户端IP和服务端IP.md)
    - [include、include_once、require、require_once之间的区别](PHP/include、include_once、require、require_once之间的区别.md)
    - [值传递和引用传递](PHP/值传递和引用传递.md)
    - [合并数组的方式有哪几种](PHP/合并数组的方式有哪几种.md)
    - [写出PHP如何连接MySQL](PHP/写出PHP如何连接MySQL.md)
  - 面向对象
    - [重写和重载](PHP/重写和重载.md)
    - [self和$this的区别](PHP/self和$this的区别.md)
    - [权限修饰符有哪些](PHP/权限修饰符有哪些.md)
    - [面向对象及其三大特性](PHP/面向对象及其三大特性.md)
    - [常见魔术方法](PHP/常见魔术方法.md)
  - 编程题
    - [给出多种方法反转字符串](PHP/给出多种方法反转字符串.md)
    - [写一个函数, 能够遍历一个文件夹下的所有文件和子文件夹](PHP/写一个函数,%20能够遍历一个文件夹下的所有文件和子文件夹.md)
    - [写一个函数将字符串'make_by_id'装换成'MakeById'](PHP/写一个函数将字符串'make_by_id'装换成'MakeById'.md)
    - [写一个函数, 统计一个字符串中另一个字符串出现的次数](PHP/写一个函数,%20统计一个字符串中另一个字符串出现的次数.md)


- MySQL
  - [mysqldump命令](MySQL/mysqldump命令.md)
  - [数据库三范式](MySQL/数据库三范式.md)
  - [MySQL索引](MySQL/MySQL索引.md)
  - [表的约束](MySQL/表的约束.md)
  - [MyISAM和InnoDB的区别](MySQL/MyISAM和InnoDB的区别.md)
  - [char和varchar数据类型的区别](MySQL/char和varchar数据类型的区别.md)
  - [TINYINT(M)中M表示的含义是什么](MySQL/TINYINT(M)中M表示的含义是什么.md)
  - [浮点型和定点型](MySQL/浮点型和定点型.md)
  - [手写建表语句和sql](MySQL/手写建表语句和sql.md)
  - [MySQL事务的4个特性](MySQL/MySQL事务的4个特性.md)
  - [事务的隔离级别](MySQL/事务的隔离级别.md)
  - [如何分析SQL查询语句的性能](MySQL/如何分析SQL查询语句的性能.md)
  - [MySQL中InnoDB的锁机制](MySQL/MySQL中InnoDB的锁机制.md)

- 设计模式
  - [设计模式简介](设计模式/设计模式简介.md)
  - [单例模式](设计模式/单例模式.md)
  - [工厂模式](设计模式/工厂模式.md)
  - [代理模式](设计模式/代理模式.md)
  - [适配器模式](设计模式/适配器模式.md)

- redis
  - [redis基础](redis/redis基础.md)
  - [redis持久化](redis/redis持久化.md)

- MQ

- [Swoole](Swoole/Swoole.md)
  - [Swoole的进程模型](Swoole/Swoole的进程模型.md)
  - [Timer](Swoole/Timer.md)
  - [异步Task](Swoole/异步Task.md)
  - [基于websocket实现多人聊天室](Swoole/基于websocket实现多人聊天室.md)
  - [HTTP](Swoole/HTTP.md)
  - [Hyperf与Lumen的压测](Swoole/Hyperf与Lumen的压测.md)
  - [协程与线程](Swoole/协程与线程.md)
  - [理解协程的执行过程](Swoole/理解协程的执行过程.md)
  
- 容器
  - [Docker基础操作](容器/Docker基础操作.md)

- 安全
  - SQL注入
  - XSS
  - CSRF

- 思维题
  - [小鼠喝牛奶](思维题/小鼠喝牛奶.md)
  - [判断4个坐标点能否组成一个矩形](思维题/判断4个坐标点能否组成一个矩形.md)
  - [判断扑克牌顺子](思维题/判断扑克牌顺子.md)
  
- 报错总结
  - [HTTP推送服务400错误](报错总结/HTTP推送服务400错误.md)