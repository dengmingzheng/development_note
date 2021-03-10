<?php

一:php源码安装
  1:在php.net下载源码包
  2:解压源码包 tar -xjvf 源码包
  3:进入解压后的文件夹 
  4:编译安装 ./configure --prefix=php安装目录(可以不写，系统会自动分配安装目录)
  5:构建:make
  6:make test
  7:make install

二:linux查看端口
   netstat anp | grep 9501

三:查看文件进程数量
   ps aft | grep tcp.php   

四:安装hiredis扩展
   1:下载源码包:https://github.com/redis/hiredis/releases   
   2:./configure --enable-async-redis
   3:make clean
   4:make -j
   5:sudo make install
     可能遇到的问题
     php-m 发现swoole消失或者是通过php --ri swoole没有显示async redis client 或 redis client
     vi ~/.bash_profile
     在最后一行添加 export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usr/local/lib
     source ~/.bash_profile
     重新编译安装swoole即可

五:phpstrom支持swoole
   1:下载ide-helper-master到本地
   2:在phpstrom中选择setting->Languages && frameworks->php
   3:添加下载到本地的扩展包    

六：协议分层
    1:数据链路层->网络层(IP)->传输层(TCP、UDP)->应用层(处理用户逻辑)    

七:TCP与UDP的对比
   1:TCP是面向连接，UDP是无连接，即发送数据之前不需要建立连接
   2:TCP提供可靠的服务。也就是说，通过TCP连接传送的数据，无差错，不丢失，不重复，按序到达。UDP尽最大努力交付，即不保证可靠交付。
   3:TCP通过效验、重传控制、序号标识、滑动窗口、确认应答实现可靠传输。如丢包时的重发控制，还可以对次序乱掉的分包进行顺序控制。
   4:UDP具有较好的实时性，工作效率比TCP高，适用于对高速传输和实时性较高的通信和广播通信。
   5:TCP对系统资源要求较多，UDP对系统资源要求较少。    

八:TCP粘包处理   
   1:什么是TCP粘包
     TCP粘包是指发送方发送的若干包数据到接收方接收时粘成一包,从接收缓冲区看，后一包数据的头紧接着前一包数据的尾
   2:TCP出现粘包的原因：
     发送方:发送方需要等缓冲区满才发送出去，造成粘包
     接收方：接收方不及时接收缓冲区的包，造成多个包接收  
   3:swoole处理粘包
     1:EOF协议(不推荐，因为需要遍历整个数据，造成资源浪费)
       'open_eof_check'=>true //开启EOF检测
       'package_eof' => '\r\n' //设置EOF
       'open_eof_split' => true //开启自动拆分  
     2:固定包头+包体协议(推荐)  
       原理:通过约定数据流的前几个字节来表示一个完整的数据有多长，从第一个数据到达之后，先通过读取固定的几个字节，解出数据包的长度，然后按这个长度继续取出后面的数据，依次循环。
       php用pack函数打包数据 unpack解包
       'open_length_check' => true //开启包长检测
       'package_length_type'=> N //设置包头的常客
       'package_length_offset'=>0 //包长度从第几个字节开始计算，比如包头长度为120字节，第10个字节为长度值，这里填入9(从0开始计数)
       'package_body_offset'=>4 //包体从第几个字节开始计算
       'package_max_length'=> 1024*1024*2 //最大允许的包长度

 九：进程的解释
     阮一峰 进程 (http://www.ruanyifeng.com/blog/2013/04/processes_and_threads.html)     


 十：协程是什么？ 
     协程是一种轻量级的线程，由用户代码来调度和管理，而不是由操作系统内核来进行调度，也就是在用户态进行。

十一：协程与普通线程有哪些区别？ 
       都说协程是一个轻量级的线程，协程和线程都适用于多任务的场景下，从这个角度上来说，协程与线程很相似，都有自己的上下文，可以共享全局变量，但不同之处在于，在同一时间可以有多个线程处于运行状态，但对于 Swoole 协程来说只能有一个，其它的协程都会处于暂停的状态。此外，普通线程是抢占式的，那个线程能得到资源由操作系统决定，而协程是协作式的，执行权由用户态自行分配。  

十二：协程编程注意事项
      1：不能存在阻塞代码     
      2：不能通过全局变量储存状态  
      3：最大协程数限制

十三:RPC:      