# MySQL中InnoDB的锁机制

### 目录
- [为什么需要锁？](#为什么需要锁？)
- [行锁和表锁](#行锁和表锁)
- [共享锁和排他锁](#共享锁和排他锁)
- [二阶段锁协议](#二阶段锁协议)
- [死锁](#死锁)


### 为什么需要锁？
数据库的锁机制使得在对数据库进行并发访问时，可以保障数据的完整性和一致性。锁冲突也是影响数据库并发访问性能的一个重要因素。锁的各种操作包括获得锁、检测锁是否是否已解除、释放锁等都是消耗资源的，。

### 行锁和表锁
行锁和表锁的粒度不同，行锁锁住的是一行或多行记录，表锁锁住的是整张表。  
行锁：开销大，加锁慢，会出现死锁，锁的粒度小，发生锁冲突的概率低，并发高。  
表锁：开销小，加锁快，不会出现死锁，锁的粒度大，发生锁冲突的概率高，并发低。  

MyISAM只支持表锁，不支持行锁，InnoDB支持表锁和行锁。InnoDB的行锁是针对索引加的行锁，如果SQL没有用到索引，会从行锁升级为表锁。  

### 共享锁和排他锁
行锁分为**共享锁**和**排他锁**。  

共享锁，又称为读锁，简称S锁。当事务对数据加上读锁后，其他事务只能对该数据加读锁，不能做任何修改操作，也就是不能添加写锁。只有当数据上的读锁被释放后，其他事务才能对其添加写锁。共享锁主要是为了支持并发的读取数据而出现的，读取数据时，不允许其他事务对当前数据进行修改操作，从而避免"不可重读"的问题的出现。  

排他锁，又称为写锁、独占锁，简称X锁。若事务T对数据对象A加上X锁，则只允许事务T读取和修改A，其他任何事务都不能再对A加任何类型的锁，直到T释放A上的锁。这就保证了其他事务在T释放A上的锁之前其他事务不能再读取和修改A。  

普通的select语句是不加锁的，select包裹在事务中，同样也是不加锁的。  

**显式加锁：**
```sql
-- 共享锁
SELECT ... LOCK IN SHARE MODE
-- 排他锁
SELECT ... FOR UPDATE
```
**隐式加锁：**  
update和delete会对查询出的记录隐式加排他锁，加锁类型和for update类似。

准备一张数据表，并插入一条数据。
```sql
CREATE TABLE `t_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '商品名称',
  `repertory` int(10) NOT NULL DEFAULT '0' COMMENT '商品库存',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

INSERT INTO t_goods (`name`, `repertory`) values ('商品1', 20);
```
共享锁：
```sql
-- 会话1
mysql> begin;
mysql> select * from t_goods where id=1 lock in share mode; -- 对id为1的记录加共享锁
+----+---------+-----------+
| id | name    | repertory |
+----+---------+-----------+
|  1 | 商品1   |        20 |
+----+---------+-----------+

-- 会话2
mysql> begin;
mysql> select * from t_goods where id=1 lock in share mode; -- 可以对id为1的记录加共享锁
+----+---------+-----------+
| id | name    | repertory |
+----+---------+-----------+
|  1 | 商品1   |        20 |
+----+---------+-----------+

-- 会话2
mysql> update t_goods set repertory=10 where id=1; -- 不可以对id为1的记录加排他锁
ERROR 1205 (HY000): Lock wait timeout exceeded; try restarting transaction
```
排他锁：
```sql
-- 会话1
mysql> begin;
mysql> select * from t_goods where id=1 for update; -- 对id为1的记录加排他锁
+----+---------+-----------+
| id | name    | repertory |
+----+---------+-----------+
|  1 | 商品1   |        20 |
+----+---------+-----------+

-- 会话2
mysql> select * from t_goods where id=1 for update; -- 不可以对id为1的记录加排他锁
ERROR 1205 (HY000): Lock wait timeout exceeded; try restarting transaction

-- 会话2
mysql> select * from t_goods where id=1 lock in share mode; -- 不可以对id为1的记录加共享锁
ERROR 1205 (HY000): Lock wait timeout exceeded; try restarting transaction
```

### 二阶段锁协议
二阶段锁：Two-phase locking(2PL)。  
InnoDB采用的是两阶段锁协议，前一个阶段为加锁，后一个阶段为解锁。在事务的执行过程中，COMMIT和ROLLBACK是解锁阶段。加锁阶段只能加锁不能解锁，一旦开始解锁，则进入解锁阶段，不能再加锁。

MySQL的行锁是在引擎层由各个引擎自己实现的。但并不是所有的引擎都支持行锁，比如MyISAM引擎就不支持行锁。不支持行锁意味着并发控制只能使用表锁，对于这种引擎的表，同一张表上任何时刻只能有一个更新在执行，这就会影响到业务并发度。InnoDB是支持行锁的，这也是MyISAM被InnoDB替代的重要原因之一。  

在下面的例子中，事务B的update语句执行时会是什么现象呢？假设字段id是表t的主键。  

![锁冲突](https://raw.githubusercontent.com/duiying/img/master/锁冲突.png)  

事务A在执行完两条update语句后，持有id为1和2两条记录的行锁(排他锁)，此时事务B的update语句会被阻塞，直到事务A执行commit后，事务A释放了id为1和2两条记录的行锁，事务B才能继续执行。  

从这个例子我们可以理解二阶段锁协议，在InnoDB事务中，行锁是在需要的时候才加上的，但并不是不需要了就立即释放锁，而是要等到事务结束时才释放，这个就是两阶段锁协议。  

知道了这个设定，对我们使用事务有什么帮助呢？那就是，如果你的事务中需要锁多个行，要把最可能造成锁冲突、最可能影响并发度的锁尽量往后放。看下面这个例子。  

假设你负责实现一个电影票在线交易业务，顾客A要在影院B购买电影票。我们简化一点，这个业务需要涉及到以下操作：  
1. 从顾客A账户余额中扣除电影票价；
2. 给影院B的账户余额增加这张电影票价；
3. 记录一条交易日志；

也就是说，要完成这个交易，我们需要update两条记录，并insert一条记录。当然，为了保证交易的原子性，我们要把这三个操作放在一个事务中。那么，你会怎样安排这三个语句在事务中的顺序呢？  

试想如果同时有另外一个顾客C要在影院B买票，那么这两个事务冲突的部分就是语句2了。因为它们要更新同一个影院账户的余额，需要修改同一行数据。  

根据两阶段锁协议，不论你怎样安排语句顺序，所有的操作需要的行锁都是在事务提交的时候才释放的。所以，如果你把语句2安排在最后，比如按照3、1、2这样的顺序，那么影院账户余额这一行的锁时间就最少。这就最大程度地减少了事务之间的锁等待，提升了并发度。  

### 死锁
死锁是指两个或两个以上的事务在执行过程中，因争夺资源而造成的一种互相等待的现象。
![死锁](https://raw.githubusercontent.com/duiying/img/master/死锁.png)  
上图中，事务A在等待事务B释放id为2的排他锁，事务B在等待事务A释放id为1的排他锁，事务A和事务B在互相等待对方的资源释放，就是进入了死锁状态。当出现死锁以后，有两种策略：  
1. 直接进入等待，直到超时。这个超时时间可以通过参数innodb_lock_wait_timeout来设置。
2. 发起死锁检测，发现死锁后，主动回滚死锁链条中的某个事务，让其他事务得以继续执行。将参数innodb_deadlock_detect设置为on，表示开启这个逻辑。  
```sql
-- 会话1
mysql> begin;
mysql> update t_goods set repertory=1 where id=1;

-- 会话2
mysql> begin;
mysql> update t_goods set repertory=1 where id=2;

-- 会话1
mysql> update t_goods set repertory=1 where id=2;

-- 会话2
mysql> update t_goods set repertory=1 where id=1;
ERROR 1213 (40001): Deadlock found when trying to get lock; try restarting transaction
```
 

### 参考
- [07 | 行锁功过：怎么减少行锁对性能的影响？](https://www.cnblogs.com/a-phper/p/10313876.html)

