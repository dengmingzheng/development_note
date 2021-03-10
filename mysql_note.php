一:mysql登录命令
   mysql -h 主机名 -u 用户名 -P 端口 -p 密码

二:查看mysql数据库
   show databases;

三:使用数据库
   use 数据库名称

四:查看数据库表
   show tables

五:查看表结构
   desc 表名

六:查看数据库版本
   1:select version()
   2:mysql --version
   3:mysql -V  

七：授权mysql远程登录
    1:grant all privileges on *.* to 'root'@'%' identified by '密码';
    2:flush privileges;   

八:mysql登录之后修改密码
   set password = password('密码');   

九:mysql命令
   1:service mysql stop 停止mysql
   2:service mysql start 启动mysql
   3:service mysql restart 重启mysql  

十：索引概述:索引是帮助mysql高效获取数据的数据结构。   

十一:索引类型:B-TREE索引、HASH索引、空间索引、全文索引   

十二:存储引擎
    1:INNODB:支持事务、行锁、外键
    2:MYISAM:支持表锁、 全文索引    

十三:SQL优化步骤
    1:查看SQL执行评率
      1:show status like 'Com_______';   
      2:show status like 'innodb_rows_%';         

    2:定位低效率执行SQL  
      1:慢查询日志--sql执行后的记录
      2:show processlist--实时记录

    3:explain分析SQL语句  
      ----------------------------------------------------------------------------------------------------
      | id | select_type |  table  | type | possible_leys | key  | key_len | ref  | rows |    Extra    |
      ----------------------------------------------------------------------------------------------------
      | 1  | SIMPLE      | tb_item | ALL  | NULL          | NULL | NULL    | NULL | 3274 | Using where |
      ----------------------------------------------------------------------------------------------------

      (1):ID:序号 ID相同--表示从上往下 ID不同--ID越大优先级越高 ID有相同和不相同的--ID相同的可以认为是一组，从上往下顺序执行；在所有的组中，ID的值越大，优先级越高，越先执行。

      (2):select_type:表示select的类型，常见的取值，如下:
          SIMPLE--简单的select查询，查询中不包含子查询或者UNION查询，即单表查询。
          PRIMARY--查询中惹包含任何复杂的子查询，最外层查询标记为该标识
          SUBQUERY--在select或where列表中包含了子查询
          DERIVED--在from列表中包含的子查询，被标记为DERIVED(衍生)mysql会递归执行这些子查询，把结果放在临时表中
          UNION--若第二个select出现在UNION之后，则标记为UNION;若UNION包含在FROM子句的子查询中，外层select将标记为:DERIVED
          UNION RESULT--从UNION表获取结果的select

          类型从上到下效率越来越低

      (3):table--表示查询的表    

      (4):type--显示的是访问类型，是较为重要的一个指标
          NULL--mysql不访问任何表，索引，直接返回结果
          system--表只有一行记录(等于系统表),这是const类型的特例，一般不会出现
          const--表示通过索引一次就找到了，const用于比较primary key 或者unique索引。因为只匹配一行数据，所以很快。如将主键置于where列表中，mysql就能将该查询转换为一个常量。const于将主键或唯一索引的所有部分与常量值进行比较。
          ep_ref--类似ref,区别在于使用的是唯一索引，使用主键的关联查询，关联查询出的记录只有一条。常见于主键或唯一索引扫描
          ref--非唯一性索引扫描，返回匹配某个单独值的所有行。本质上也是一种索引访问，返回所有匹配某个单独值的所有行（多个）
          range--只检索给定返回的行，使用一个索引来选择行。where之后出现between,<,>,in等操作
          index--index与all的区别为index类型只是遍历了索引树，通常比all快，all是遍历数据文件
          all--将遍历全表以找到匹配的行

          从上往下执行的效率越来越低，一般来说，优化需要保证查询至少达到range级别，最好达到ref就可以了。

      (5):possible_leys--显示可能应用在这张表的索引，一个或多个

      (6):key:实际使用的索引,如果为null,则没有使用索引

      (7):key_len:表示索引中使用的字节数，该值为索引字段最大可能长度，并非实际使用长度，在不损失精确性的前提下，长度越短越好。  

      (8):rows--扫描行的数量

      (9):extra--其他的额外执行计划信息，如下:
          using filesort--说明mysql会对数据使用一个外部的索引排序，而不是按照表内的索引顺序进行读取，称为'文件排序'。--效率低
          using temporary--使用了临时表保存中间结果，mysql在对查询结果排序时使用临时表。常见于order by和group by。--效率低
          using index--表示相应的select操作使用了覆盖索引，避免访问表的数据行，效率不错。  

          出现前面两个就表示需要优化性能了。

    4:show profile分析SQL
      mysql从5.0.37版本开始增加了对show profiles和show profile语句的支持。show profiles能够在做sql优化时帮助我们了解时间都耗费到哪里去了。通过have_profiling参数，能够看到当前mysql是否支持profile--select @@have_profiling;默认profiling是关闭的，可以通过set语句在session级别开启profiling;查看profiling开启还是关闭--select @@profiling; 开启profiling--set profiling=1。

      show profiles--查询执行操作所耗费的时间
      show profile for query query_id--查看该sql执行过程中每个线程的状态和消耗时间。
      show all profile for query query_id--查询系统所耗费的资源
      show cpu profile for query query_id--查询cpu使用的效率

    5:trace分析优化器执行计划  

十四：索引的使用
    1：避免索引失效
       (1):全值匹配
       (2):最左前缀法则--如果索引了多列，要遵守最左前缀法则。指的是查询从索引的最左前列开始，并且不跳过索引中的列。
       (3):范围查询右边的列，不能使用索引。
       (4):不要在索引列上进行运算操作，索引将失效。
       (5):字符串不加单引号，造成索引失效。
       (6):尽量使用覆盖索引，避免select *。
       (7):用or分割开的条件，如果or前的条件中的列有索引，而后面的列中没有索引，那么涉及的索引都不会被用到。
       (8):以%开头的like模糊查询,索引失效。--通过覆盖索引解决
       (9):如果mysql评估使用索引比全表更慢，则不使用索引。
       (10):is null,is not null 有时走索引。如果数据库中null的值比较多，where条件is null会不走索引，因为mysql数据库会评估走全表扫描比走索引更快。
       (11):in走索引，not in索引失效。
       (12):单列索引和复合索引,尽量使用复合索引，而少使用单列索引。

十五:SQL优化
     1:大批量插入数据
       (1):对于Innodb类型的表，有一下几种方式可以提高导入的效率
           1:主键顺序插入
           2:关闭唯一性效验
           3:关闭事务   

       (2):优化insert语句
           1：如果需要同时对一张表插入很多行数据时，应该尽量一次性插入，不要一条一条插入。这种方式将减少数据库与客户端之间的连接和关闭等消耗。  
           2：在事务中进行数据插入改为手动提交。
           3：数据有序插入。

        (3):优化order by语句    
           1:mysql的两种排序方式：filesort(效率低)和using index(效率高);尽量减少额外的排序，通过索引直接返回有序数据，where条件和order by使用相同的索引，并且order by的顺序和索引顺序相同，并且order by的字段都是升序，或者都是降序。否则肯定需要额外的操作，这样就会出现filesort。 

        (4):使用多表查询代替嵌套查询。

十六:1:应用优化
	     1:使用连接池
	     2:减少对mysql的访问
	     3:负载均衡        
     2:查询缓存优化：开启mysql的查询缓存，当执行完全相同的sql语句的时候，服务器就会直接从缓存中读取结果，当数据被修改，之前的缓存会失效，修改比较频繁的表不适合做查询缓存。
        (1):查看当前mysql数据库是否支持查询缓存--show variables like 'have_query_cache'   
        (2):查看mysql是否开启了查询缓存--show variables like 'query_cache_type'
        (3):查看查询缓存的占用大小--show variables like 'query_cache_size'  
        (4):查看查询缓存的状态变量--show variables like 'Qcache%' 
        (5):开启查询缓存--在my.cnf配置文件中添加query_cache_type=1或on
       
       查询缓存失效的情况:
        (1):sql语句不一致的情况，要想命中查询缓存，查询的sql语句必须一致。
            select count(*) from user和Select count(*) from user--语句不一致，查询缓存失效
        (2):当查询语句中有一些不确定时，则不会缓存。如：now(),current_date(),curdate(),curtime(),rand(),uuid(),user(),database()。因为每次查询的结果都不一致，所以不会缓存。  
        (3):不使用任何表的查询。 
        (4):查询mysql系统数据库的时候，不会走缓存。
        (5):当数据库中的表数据发生变动时，不会走缓存。 

      3:mysql内存管理及优化
        1:内存优化原则：
          将尽量多的内存分配给mysql做缓存，但要给操作系统和其他程序预留足够内存。
          myisam存储引擎的数据文件读取依赖于操作系统自身的IO缓存，因此，如果有myisam表,就要预留更多的内存给操作系统做IO缓存。
          排序区，连接区等缓存是分配给每个数据库回话(session)专用的，其默认值的设置要根据最大连接数合理分配，如果设置太大，不但浪费资源，而且在并发连接较高时会导致物理内存消耗。

        2:myisam内存优化:
          myisam存储引擎使用key_buffer缓存索引块，加速myisam索引的读写速度。对于myisam表的数据块，myisam没有特别的缓存机制，完全依赖于操作系统的IO缓存。

          key_buffer_size决定myisam索引块缓存区的大小，直接影响到myisam表的存取效率。可以在mysql参数文件中设置key_buffer_size的值，对于一般myisam数据库，建议至少将1/4可用内存分配给key_buffer_size。

          如果需要经常顺序扫描myisam表，可以通过增大read_buffer_size的值来改善性能，但需要注意的是read_buffer_size是每个session独占的，如果默认值设置太大，就会造成内存浪费。

          对于需要做排序的myisam表的查询，如带有order by子句的sql,适当增加read_rnd_buffer_size的值，可以改善此类的sql性能。但需要注意的是read_rnd_buffer_size是每个session独占的，如果默认值设置太大，就会造成内存浪费。

        3:innodb内存优化
          innodb用一块内存区做IO缓存池，该缓存池不仅用来缓存innodb的索引块，而且也用来缓存innodb的数据块。

          innodb_buffer_pool_size,该变量决定了innodb存储引擎表数据和索引数据的最大缓冲区大小。在保证操作系统及其他程序有足够内存可用的情况下，该值越大，缓存命中率越高，访问innodb表需要的磁盘IO就越少，性能也就越高。 innodb_buffer_pool_size=512M

          innodb_log_buffer_size决定了innodb重做日志缓存的大小，对于可能产生大量更新记录的大事务，增加该值的大小，可以避免innodb在事务提交前就执行不必要的日志写入磁盘操作。innodb_log_buffer_size=10M 

        4:mysql并发参数调整
          从实现上来说，mysql server是多线程结构，包括后台线程和客户服务线程。多线程可以有效利用服务器资源，提高数据库的并发性能。在mysql中，控制并发连接和线程的主要参数包括max_connections,back_log,thread_cache_size,table_open_cache。

          采用max_connections控制允许连接到mysql数据库的最大数量，默认值是151。如果状态变量connection_errors_max_connections不为零，并且一直增长，则说明不断有连接请求因数据库连接数已达到允许最大值而失败，这是可以考虑增大max_connections的值。
          mysql最大可支持的连接数，取决于很多因素，包括给定操作系统平台的线程库的质量、内存大小、每个连接的负荷、CPU的处理速度、期望的响应时间等。

          back_log参数控制mysql监听TCP端口时设置的积压请求栈大小。如果mysql的连接数达到max_connections时，新来的请求将会被存在堆栈中，以等待某一连接释放资源，该堆栈的数量即back_log,如果等待连接的数量超过back_log,将不被授予连接资源，将会报错。
          如果需要数据库在较短的时间内处理大量连接请求，可以考虑适当增大back_log的值。

          table_open_cache该参数用来控制所有sql语句执行线程可打开表缓存的数量，而在执行sql语句时，每个sql执行线程至少要打开一个表缓存。该参数的值应该根据设置的最大连接数max_connections以及每个连接执行关联查询中涉及的表的最大数量来设定。

          thread_cache_size,为了加快连接数据库的速度，mysql会缓存一点数量的客户服务线程以备重用，通过该参数可控制mysql缓存客户服务线程的数量。

          innodb_lock_wait_timeout该参数是用来设置innodb事务等待行锁的时间,默认值是50ms,可以根据需要进行动态设置。对于需要快速反馈的业务系统来说，可以将行锁的等待时间调小，以避免事务长时间挂起；对于后台运行的批量处理程序来说，可以将行锁的等待时间调大，以避免发生大的回滚操作。

十七:mysql锁问题:
        1:锁概述:
          锁是计算机协调多个进程或线程并发访问某一资源的机制(避免争抢)。

        2:锁分类:    
          从对数据操作的粒度分:
	          表锁：操作时，会锁定整个表。
	          行锁：操作时，会锁定当前操作行。      

          从对数据操作的类型分:
              读锁(共享锁):针对同一份数据，多个操作可以同时进行而不会互相影响。
              写锁(排他锁):当前操作没有完成之前，它会阻断其他写锁和读锁。

              读锁会阻塞写，不会阻塞读。写锁会阻塞写也会阻塞读。

        3:INNODB
          事务是由一组SQL语句组成的逻辑处理单元。     
          事务的四大特性:原子性、一致性、持久性、隔离性。

          并发事务处理带来的问题:
	          1:丢失更新--当两个或多个事务选择同一行，最初的事务修改的值，会被后面的事务修改的值覆盖。
	          2:脏读--当一个事务正在访问数据，并且对数据进行了修改，而这种修改还没有提交到数据库中，这时另外一个事务也访问这个数据，然后使用了这个数据。 简单的说就是一个事务读取了另一个事务还没有提交的数据。
	          3:不可重复读--一个事务在读取某些数据后的某个时间，再次读取以前读过的数据，却发现和以前读出的数据不一致。
	          4:幻读--一个事务按照相同的查询条件重新读取以前查询过的数据，却发现其他事务插入了满足其查询条件的新数据。

          事务隔离级别
              1:Read uncommitted(读未提交)
              2:Read committed(读已提交)
              3:Repeatable read(可重复读);数据库默认的隔离级别
              4:Serializable(串行化)

              优化建议:
               1:尽可能让所有数据检索都能通过索引来完成，避免无索引行锁升级为表锁。
               2:合理设计索引，尽量缩小锁的范围。
               3:尽可能减少索引条件，及索引范围，避免间隙锁。
               4:尽量控制事务大小，减少锁定资源量和时间长度。
               5:尽可能使用低级别事务隔离。

十八：mysql日志
      1:错误日志--该日志是mysql中最重要的日志之一，记录了当mysql启动和停止时，以及服务器在运行过程中发生任何严重错误时的相关信息。
        查看错误日志位置指令--show variables like 'log_error%';   

      2:二进制日志:
        二进制日志记录了所有的DDL语句和DML语句，但是不包括数据查询语句。此日志对于灾难时的数据恢复起着极其重要的作用，mysql的主从复制，就是通过该binlog日志实现的。
        二进制日志，默认情况下是没有开启的，需要到mysql的配置文件中开启，并配置mysql日志的格式。
        log_bin=mysqlbin //配置开启binlog日志，日志的文件前缀为mysqlbin----->生成的文件名如:mysqlbin.001,mysqlbin.002
        binlog_format=STATEMENT //配置二进制日志的格式  
        mysqlbin.index-->该文件是日志索引文件，记录日志的文件名。
        mysqlbin.001-->日志文件

        日志格式:
          1:STATEMENT--该日志格式在日志文件中记录的都是sql语句，每一条对数据进行修改的sql都会记录在日志文件中，通过mysql提供的mysqlbinlog工具，可以清晰的查看到每条语句的文本。主从复制的时候，从库会将日志解析为原文本，并在从库重新执行一次。
          2:ROW--该日志格式在日志文件中记录的是每一行的数据变更，而不是记录sql语句。比如，执行sql语句：update book set status='1',如果是STATEMENT日志格式，在日志中会记录一行sql语句；如果是ROW，由于是对全表进行更新，也就是每一行记录都会发生变更，ROW格式的日志中会记录每一行的数据变更。
          3:MIXED--这是目前mysql默认的日志格式，即混合了STATEMENT和ROW两种格式。默认情况下采用STATEMENT,但是在一些特殊情况下采用ROW来进行记录。MIXED格式能尽量利用两种格式的优点，而避开他们的缺点。           
        
        日志删除:
          1:通过Reset Master指令删除全部binlog日志，删除之后，日志编号，将从xxxx.000001重新开始。
          2:purge master logs to 'mysqlbin.********',该指令将删除******编号之前的所有日志。
          3:purge master logs before '2020-08-27 23:16:58'，该命令删除日志为该时间之前产生的所有日志。
          4:设置参数--expire_logs_days=3,此参数的含义是设置日志的过期天数，过了指定的天数后日志将会被自动删除，这样将有利于减少DBA管理日志的工作量。

        查询日志:
          查询日志中记录了客户端的所有操作语句，而二进制日志不包含查询数据的sql语句。
          默认情况下，查询日志是未开启的。如果需要开启查询日志，可以设置以下配置:
          general_log=1 //该选项用来开启查询日志
          general_log_file=query_log.log //设置日志的文件名，如果没有指定，默认的文件名为host_name.log 

        慢查询日志:
          慢查询日志记录了所有执行时间超过参数long_query_time设置值并且扫描记录数不小于min_examined_row_limit的所有的sql语句的日志。
          long_query_time默认为10秒，最小为0，精度可以到微秒。
          慢查询日志默认是关闭的，可以通过两个参数来控制慢查询日志:
          show_query_log=1 //该参数用来控制慢查询日志是否开启
          show_query_log_file=slow_query.log //该参数用来指定慢查询日志的文件名
          long_query_time=10 //该选项用来配置查询的时间限制，超过这个时间将认为是慢查询，将需要进行日志记录。   

      3:mysql复制
          复制是指将主数据库的DDL和DML操作通过二进制日志传到从库服务器中，然后在从库上对这些日志重新执行，从而使得从库和主库的数据保持同步。

          主从集群的搭建:
            1:master--在master的配置文件(/usr/my.cnf)中，配置如下内容
              (1):servier_id=1 //mysql服务ID，保证整个集群环境中唯一
              (2):log-bin=/var/lib/mysql/mysqlbin //mysql binlog日志的存储路径和文件名
              (3):binlog-ignore-db=mysql //忽略的数据，指的是不需要同步的数据库
              (4):read-only=0 //是否只读，1代表只读 0代表读写

            2:创建同步数据的账户，并且进行授权操作
              (1):grant replication slave on *.* to '账号'@'从服务器IP地址' identified by '密码';
              (2):flush privileges; //刷新权限列表

            3:查看master状态
              (1):show master suatus;
                  字段含义:
                  file:从哪个日志文件开始推送日志文件
                  position:从哪个位置开始推送日志
                  binlog_ignore_db:指定不需要同步的数据库

            4:slave节点的配置
              (1):在slave端配置文件中，配置如下内容
                  server-id=2 //mysql服务端ID，唯一
                  log-bin=/var/lib/mysql/mysqlbin //指定binlog日志

              (2):service mysql restart;
              (3):change master to master_host='主节点IP地址'，master_user='主节点配置的同步账号',master_password='主节点配置的同步账号密码',master_log_file='mysqlbin.000001',master_log_pos=413; //指定当前从库对应的主库的IP地址，用户名，密码，从哪个日志文件开始的那个位置开始同步推送日志；

              (4):开启同步:
                  start slave;
                  show slave status\G;  
                  Slave_IO_Running:yes;Slave_SQL_Running:yes --只要这两个正常，同步基本就没问题了。  
