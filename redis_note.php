一：redis淘汰简介
    1:Redis官方给的警告，当内存不足时，Redis会根据配置的缓存策略淘汰部分keys，以保证写入成功。当无淘汰策略时或没有找到适合淘汰的key时，Redis直接返回out of memory错误。
    2:6种数据淘汰策略
        1、volatile-lru：从已设置过期时间的数据集（server.db[i].expires）中挑选最近最少使用的数据淘汰
		2、volatile-ttl：从已设置过期时间的数据集（server.db[i].expires）中挑选将要过期的数据淘汰
		3、volatile-random：从已设置过期时间的数据集（server.db[i].expires）中任意选择数据淘汰
		4、allkeys-lru：从数据集（server.db[i].dict）中挑选最近最少使用的数据淘汰
		5、allkeys-random：从数据集（server.db[i].dict）中任意选择数据淘汰
		6、no-enviction（驱逐）：禁止驱逐数据

二:缓存穿透
     描述：缓存穿透是指缓存和数据库中都没有的数据，而用户不断发起请求。由于缓存是不命中时被动写的，并且出于容错考虑，如果从存储层查不到数据则不写入缓存，这将导致这个不存在的数据每次请求都要到存储层去查询，失去了缓存的意义。
     解决方案：接口层增加校验，如用户鉴权校验，id做基础校验，id<=0的直接拦截；	

三:缓存击穿
     描述：缓存击穿是指缓存中没有但数据库中有的数据（一般是缓存时间到期），这时由于并发用户特别多，同时读缓存没读到数据，又同时去数据库去取数据，引起数据库压力瞬间增大，造成过大压力。
     解决方案：
             1、设置热点数据永远不过期。    
             2、接口限流与熔断，降级。
             3、布隆过滤器。
             4、加互斥锁 	

四:缓存雪崩
     描述：缓存雪崩是指缓存中数据大批量到过期时间，而查询数据量巨大，引起数据库压力过大甚至down机。和缓存击穿不同的是，缓存击穿指并发查同一条数据，缓存雪崩是不同数据都过期了，很     多数据都查不到从而查数据库。             
     解决方案：
            1:缓存数据的过期时间设置随机，防止同一时间大量数据过期现象发生。
			2:如果缓存数据库是分布式部署，将热点数据均匀分布在不同搞得缓存数据库中。
			3:设置热点数据永远不过期。

五:Redis中两种持久化机制RDB和AOF
   RDB 的优势和劣势
   优势:
	（1）RDB文件紧凑，全量备份，非常适合用于进行备份和灾难恢复。
	（2）生成RDB文件的时候，redis主进程会fork()一个子进程来处理所有保存工作，主进程不需要进行任何磁盘IO操作。
	（3）RDB 在恢复大数据集时的速度比 AOF 的恢复速度要快。
   劣势
     RDB快照是一次全量备份，存储的是内存数据的二进制序列化形式，存储上非常紧凑。当进行快照持久化时，会开启一个子进程专门负责快照持久化，子进程会拥有父进程的内存数据，父进程修改内存子进程不会反应出来，所以在快照持久化期间修改的数据不会被保存，可能丢失数据。

   AOF的优势和劣势 
   优点
	（1）AOF可以更好的保护数据不丢失，一般AOF会每隔1秒，通过一个后台线程执行一次fsync操作，最多丢失1秒钟的数据。
	（2）AOF日志文件没有任何磁盘寻址的开销，写入性能非常高，文件不容易破损。
	（3）AOF日志文件即使过大的时候，出现后台重写操作，也不会影响客户端的读写。
	（4）AOF日志文件的命令通过非常可读的方式进行记录，这个特性非常适合做灾难性的误删除的紧急恢复。比如某人不小心用flushall命令清空了所有数据，只要这个时候后台rewrite还没有发生，那么就可以立即拷贝AOF文件，将最后一条flushall命令给删了，然后再将该AOF文件放回去，就可以通过恢复机制，自动恢复所有数据

    缺点
	（1）对于同一份数据来说，AOF日志文件通常比RDB数据快照文件更大
	（2）AOF开启后，支持的写QPS会比RDB支持的写QPS低，因为AOF一般会配置成每秒fsync一次日志文件，当然，每秒一次fsync，性能也还是很高的
	（3）以前AOF发生过bug，就是通过AOF记录的日志，进行数据恢复的时候，没有恢复一模一样的数据出来。
