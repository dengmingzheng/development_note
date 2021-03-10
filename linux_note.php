<?php

一：分区类型

    1：主分区：最多只能有4个

    2：扩展分区：最多只能有一个，主分区加扩展分区最多有4个，扩展分区不能写入数据，只能包含逻辑分区。

二：Linux常用命令

    1：ls -a  显示当前文件夹下的所有文件，包括隐藏文件。.开头的文件都是隐藏文件。

    2：ls -l  显示文件的详细信息。
       ls -lh 显示文件的详细信息和文件大小。
       drwxr-xr-x. 2 root root 4096 8月  15 01:37 dmz
       _:表示文件。d:表示目录。l:表示软链接。
       r:读 w:写 x:执行
       rwx：表示所有者拥有读写执行
       r-x：表示所属组拥有读和执行
       r-x：表示其他人拥有读和执行
       2：表示文件被引用的次数
       root：表示文件的所有者
       root：所属组
       4096：文件的大小  
       8月  15 01:37  文件的创建时间或者修改时间 

    3：ls -i  查询文件的i节点

    目录处理命令

    1：mkdir 目录 创建目录
       mkdir -p 递归创建目录

    2：cd 目录 切换目录
       cd .. 返回上一级目录

    3：pwd 显示当前工作路径

    4：rmdir 目录  删除空目录

    5：cp  [原文件] [目标文件]
       -r 复制目录  -p 保留文件属性

    6：ctrl+l或clear 清屏

    7：mv [原文件或目录] [目标文件或目录] 剪切文件或目录

    8：rm -f 文件  删除文件
       rm -rf 目录 删除目录
       -r 删除目录 -f 强制执行

    9：ctrl+c 终止命令

    文件处理命令

    1：touch 文件名  创建文件

    2：cat 文件   浏览文件内容

    3：tac 文件   倒叙显示文件内容

    4：more 文件  分页显示文件
       空格或f    分页
       回车       换行
       q          退出

    5：less       分页显示文件内容(可向上翻)
       Pgup       向上翻页
       向上箭头   向上换行
       /关键字    搜索关键字
       n          下一个

    6：head  文件 默认显示文件的10行
       head  -n 7 文件  显示文件的前7行

    7：tail 文件  显示文件倒数10行
       tail -n  7 文件  显示文件的最后7行
       tail -f 文件  动态的显示文件末尾内容

    8：ln -s 原文件  目标文件 创建软链接文件
       ln 原文件  目标文件    创建硬链接文件

    权限管理命令

    1：chmod 777 文件或目录  更改文件或目录的权限
       r:4 w:2 x:1
       -R 递归更改

    2：r  读权限  可以查看文件内容   可以列出目录中的内容
       w  写权限  可以修改文件内容   可以在目录中创建，删除文件
       x  执行权限 可以执行文件      可以进入目录

    3：删除文件要对文件所在的目录有写权限

    4：chown 用户名 文件或目录  更改文件或目录的所有者

    5：chgrp 用户名 文件或目录  更改文件或目录的所属组

    6：umask -S 显示 设置文件的缺省权限

    7：linux下新建的文件默认是没有可执行权限 即没有x

    文件查找命令

    1：find /etc -name init  在目录/etc中查找文件init
       iname 不区分大小写查找
       ?匹配一个字符 *匹配任意字符

    2: locate 文件  在文件资料库中查找文件
       -i 不区分大小写查找

    3：updatedb 更新文件资料库

    4：wihch 命令  搜索命令所在目录及别名信息

    5：whereis 搜索命令所在目录及帮助文档路径

    6：grep -iv [指定字符串] [文件]  在文件中搜索字符串匹配的行并输出
       -i 不区分大小写 -v排除指定字符串
       范例：grep mysql /root/install.log

    帮助命令
    1：man [命令或配置文件] 

    用户管理命令

    1：useradd 用户名  添加用户名

    2：passwd  用户名  给用户设置密码

    3：who  查看登录用户
       dmz      tty1         2016-08-18 21:51 (:0)
       dmz      pts/0        2016-08-18 21:51 (192.168.245.1)
       dmz  用户名  tty 本地登录  pts  远程登录

    4：w  查看登录用户更详细的信息

    5：uptime 查看系统运行时间


    压缩解压命令

    1：gzip [文件]  压缩文件

    2：gunzip [文件]  解压文件 

    3：tar [-zcfv] [压缩后文件名] [打包的目录] 打包目录
       -c：打包 -v：显示详细信息 -f：指定文件名 -z：打包同时压缩

    4：tar [-zfvx] 
       -x：解包 -v：显示详细信息 -f：指定文件名 -z：解压缩

    5：zip [-r]  [压缩后文件名] [需要压缩的文件或目录]   压缩文件或目录
       -r：压缩目录

    6：bzip2 [-k] 文件   压缩文件  （用来压缩大文件）
       -k 产生压缩文件后保留原文件

    网络命令

    1：write [用户名] 给在线用户发信息 以ctrl+d保存结束

    2：wall [信息]  给所有用户发信息

    3：ping IP地址  测试网络连接性  一直ping下去  ctrl+c 退出
       -c 5 ping 5次

    4：ifconfig 网卡名称 IP地址   查看和设置网卡信息

    5：mail [用户]  发信   mail 看信

    6：last  列出目前和过去的用户登录信息

    7：netstat [选项]  显示网络相关信息

    关机重启命令

    1：shutdown [选项] 时间
       -c:取消前一个关机命令 -h:关机  -r:重启
    2：重启命令：reboot init 6  关机命令：init 0
    3：runlevel 查看系统运行级别
    4：logout 退出登录

    VIM编辑器

    1：vim 文件  进入文件
    2：wq  保存退出
    3: q!  不保存退出
    4：set nu 显示行数
    5：a 在光标所在字符后插入
    6：A 在光标所在行尾插入
    7：i 在光标所在字符前插入
    8：I 在光标所在行行首插入
    9：o 在光标下插入新行
    10：O 在光标上插入新行
    

    软件包分类：1：源码包 2：二进制包(rpm包)

    一：源码包的优点：
        1：开源
        2：可以自由选择功能
        3：软件是编译安装，所以更加适合自己的系统，更加稳定效率也更高
        4：卸载方便

    二：源码包安装的缺点：
        1：安装过程步骤过多，尤其安装较大的软件集合时，容易出现拼写错误
        2：编译时间过程较长
        3：因为是编译安装，在安装过程中报错很难解决

    三：rpm包的优点
        1：包管理系统简单，只通过几个简单的命令包的安装、升级、卸载
        2：安装速度快

    四：rpm包的缺点
        1：经过编辑，不能再看到源码
        2：功能选择不如源码包灵活
        3：依赖性

    五：1:rpm安装
        rpm -ivh 包全名
        -i:安装 -v:显示详细信息 -h:显示进度 -U:升级 -e:卸载 --nodeps:不检测依赖性

        2:查询rpm包是否安装
          rpm -q 包名
          -i:查询软件信息 -a:查询所有安装的rpm包

        3：yum安装
           yum -y install 包名
           -y:自动回答yes -install:安装