
一：docker的安装

    # 1、yum 包更新到最新 
	yum update

	# 2、安装需要的软件包， yum-util 提供yum-config-manager功能，另外两个是devicemapper驱动依赖的 
	yum install -y yum-utils device-mapper-persistent-data lvm2

	# 3、 设置yum源
	yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo

	# 4、 安装docker，出现输入的界面都按 y 
	yum install -y docker-ce

	# 5、 查看docker版本，验证是否验证成功
	docker -v

二：配置docker镜像加速器

    # 1、登录阿里云，进入控制台，搜索容器镜像服务，点击镜像加速器连接

    # 2、创建docker配置文件夹
    sudo mkdir -p /etc/docker

    # 3、在docker配置文件夹中创建daemon配置文件，输入镜像加速器地址
	sudo tee /etc/docker/daemon.json <<-'EOF'
	{
	  "registry-mirrors": ["https://bfmoqmh4.mirror.aliyuncs.com"]
	}
	EOF

	# 4、重新加载配置
	sudo systemctl daemon-reload

	# 5、重新启动docker服务
	sudo systemctl restart docker

三：docker进程命令

    # 1、启动docker
    systemctl start docker

    # 2、查看docker状态
    systemctl status docker

    # 3、停止docker
    systemctl stop docker

    # 4、重启docker
    systemctl restart docker

    # 5、开机启动docker
    systemctl enable docker

四：docker镜像命令

    # 1、查看本地仓库镜像
    docker images
    root@dengmingzheng-virtual-machine:/home/dengmingzheng# docker images
	REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
	redis               latest              975fe4b9f798        7 days ago          98.3MB
	nginx               latest              f7bb5701a33c        3 months ago        126MB
	hello-world         latest              fce289e99eb9        15 months ago       1.84kB

	REPOSITORY:仓库名称 TAG:版本号 IMAGE ID:镜像ID CREATED：创建时间 SIZE：镜像大小

	# 2、搜索远程仓库镜像
	docker search 镜像名称
    
    # 3、拉取远程镜像
    docker pull redis //下载的是redis最新版本
    docker pull redis:3.2 //下载的是redis3.2版本

    # 4、删除本地仓库镜像
    docker rmi 镜像ID
    docker rmi 仓库名称:版本号

    # 5、删除本地仓库所有镜像
    docker rmi `docker images -q`

    # 6、查看本地仓库所有镜像ID号
    docker images -q

五：docker容器命令    

    # 1、查看容器
    docker ps //查看所有正在运行的容器
    docker ps -a //查看所有容器

    root@dengmingzheng-virtual-machine:/home/dengmingzheng# docker ps -a
	CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS                    PORTS               NAMES
	96272393cbe7        hello-world         "/hello"            3 months ago        Exited (0) 3 months ago                       goofy_chandrasekhar
	b002857fdb8b        hello-world         "/hello"            3 months ago        Exited (0) 3 months ago                       serene_tharp

    CONTAINER ID:容器ID  IMAGE：容器依赖的镜像名称 COMMAND:进入容器的初始化指令  CREATED:创建时间  STATUS:容器运行状态  PORTS：容器端口  NAMES：容器名称

    # 2、创建并启动容器
    docker run -it --name=redis 镜像名称:版本号 /bin/bash
    docker run -id --name=redis 镜像名称:版本号 /bin/bash

    参数说明：
	 -i：保持容器运行。通常与 -t 同时使用。加入it这两个参数后，容器创建后自动进入容器中，退出容器后，容器自动关闭。
	 -t：为容器重新分配一个伪输入终端，通常与 -i 同时使用。
	 -d：以守护（后台）模式运行容器。创建一个容器在后台运行，需要使用docker exec 进入容器。退出后，容器不会关闭。
	 -it 创建的容器一般称为交互式容器，-id 创建的容器一般称为守护式容器
	 --name：为创建的容器命名
	 -p : 端口名称
	 -v : 数据卷挂载
	 -link: 容器连接
	 --network : 指定网络类型 (bridge：桥接网络 none：无指定网络 host： 主机网络)
     /bin/bash : 进入容器的初始化指令

    # 3、退出容器
    exit

    # 4、进入容器
    docker exec -it 容器名称 /bin/bash 

    # 5、关闭容器
    docker stop 容器名称
    docker stop `docker ps -aq' 或 docker stop $(docker ps -aq) :停止所有容器

    # 6、启动容器
    docker start 容器名称
    docker start `docker ps -aq' 或 docker start $(docker ps -aq) :启动所有容器

    # 7、删除容器
    docker rm 容器名称
    docker rm 容器ID
    docker rm `docker ps -aq` 或 docker rm $(docker ps -aq) //删除所有容器
    注意：正在运行的容器不能删除，必须先停止容器再进行删除

    # 8、查看容器信息
    docker inspect  容器名称


六：docker容器的数据卷
    
    # 1、数据卷的概念
	数据卷是宿主机中的一个目录或文件
	当容器目录和数据卷目录绑定后，对方的修改会立即同步
	一个数据卷可以被多个容器同时挂载
	一个容器也可以被挂载多个数据卷
   
    # 2、数据卷的作用
	容器数据持久化
	外部机器和容器间接通信
	容器之间数据交换

	# 3、配置数据卷
	在创建或者启动容器时，使用-v参数设置数据卷
	docker run -it --name=redis -v 宿主机目录(文件):容器内目录(文件) 镜像名称:版本号 /bin/bash
    注意事项：
	1. 目录必须是绝对路径
	2. 如果目录不存在，会自动创建
	3. 可以挂载多个数据卷

	# 4、配置数据卷容器
	1. 创建启动c3数据卷容器，使用 –v 参数 设置数据卷
	docker run –it --name=c3 –v /volume centos:7 /bin/bash

	2. 创建启动 c1 c2 容器，使用 –-volumes-from 参数 设置数据卷
	docker run –it --name=c1 --volumes-from c3 centos:7 /bin/bash
	docker run –it --name=c2 --volumes-from c3 centos:7 /bin/bash


七：dockerfile
   
   # 1、dockerfile镜像原理

    • Docker镜像是由特殊的文件系统叠加而成
	• 最底端是 bootfs，并使用宿主机的bootfs
	• 第二层是 root文件系统rootfs,称为base image
	• 然后再往上可以叠加其他的镜像文件
	• 统一文件系统（Union File System）技术能够将不同的
	层整合成一个文件系统，为这些层提供了一个统一的视角
	，这样就隐藏了多层的存在，在用户的角度看来，只存在
	一个文件系统。
	• 一个镜像可以放在另一个镜像的上面。位于下面的镜像称
	为父镜像，最底部的镜像成为基础镜像。
	• 当从一个镜像启动容器时，Docker会在最顶层加载一个读
	写文件系统作为容器

   # 2、容器转镜像
   docker commit  容器id  镜像名称: 版本号
   docker save -o 压缩文件名称  镜像名称: 版本号
   docker load –i 	 压缩文件名称
   docker export 容器id/name >文件名.tar
   docker import 文件名.tar

   类型          导出的对象                   导出文件大小   是否可回滚到历史层
   export&import 将容器导出                   小             否
   save&load     用来将一个或者多个image打包   大             是

   # 3、dockerfile概念
	• Dockerfile 是一个文本文件
	• 包含了一条条的指令
	• 每一条指令构建一层，基于基础镜像，最终构建出一个新的镜像
	• 对于开发人员：可以为开发团队提供一个完全一致的开发环境
	• 对于测试人员：可以直接拿开发时所构建的镜像或者通过Dockerfile文件构建一个新的镜像开始工作了
	• 对于运维人员：在部署时，可以实现应用的无缝移植

   # 4、dockerfile指令
     1:FORM--指定基础镜像
     2:RUN--执行命令
     3:COPY--复制指令
     4:ADD--解压缩
     5:CMD--容器启动命令
     6:ENV--设置环境变量
     7:ARG--设置参数
     8:VOLUME--定义匿名卷
     9:EXPOSE--声明端口
     10:WORKDIR--指定工作目录
     11:USER--指定当前用户
     12:HEALTHCHECK--健康检查
   # 5、使用dockerfile构建镜像
     在当前dockerfile文件目录中使用:docker build -t 镜像名称 .
     . 表示上下文


八：redis主从复制     



     