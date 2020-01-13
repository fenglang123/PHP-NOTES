# MyISAM和InnoDB的区别

MySQL5.5以后，默认存储引擎是InnoDB。

1. InnoDB支持事务；MyISAM不支持；
2. InnoDB支持外键；MyISAM不支持；
3. MyISAM支持全文索引；InnoDB5.6版本及以后支持全文索引；
4. InnoDB支持行锁，并发较大；MyISAM仅支持表锁；
5. 使用delete删除表的时候，InnoDB是逐行删除；MyISAM是先DROP表，然后重新建表，MyISAM的效率快；
6. 对于select count(*) from 表名; InnoDB会遍历整个表来计算行数；MyISAM因为保存了表的行数可以直接取出；(但是如果加了WHERE条件，MyISAM和InnoDB都会遍历整个表来计算行号)
7. MyISAM强调的是性能，查询速度比InnoDB快；
