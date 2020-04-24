一：git创建远程仓库:
    git remote add origin git@github.com:用户名/项目名称.git

二：git提交文件到远程仓库
    git push -u origin master

三: git初始化:    
    git init

四: git提交文件到缓存区
    git add -A

五：git提交文件到本地仓库
    git commit -m '备注' 

六：git拉取远程仓库代码
    git pull

七：克隆远程仓库项目到本地
    git clone 网址     

八：查看分支
    git branch

九：创建分支
    1：git branch 分支名称
    2：git checkout -b 分支名称

十：删除远程仓库分支                  
    git push origin --delete 分支名称

十一：删除本地分支
    git branch -d 分支名称

十二：切换分支        
    git checkout 分支名称

十三：删除远程文件   
    git rm --cached --文件路径 

十四：删除远程文件夹    
    git rm --cached -r --文件夹路径

十五：合并分支
  	1:切换到主分支
      git checkout master
  	2:然后再合并分支
      git merge 分支名称    

十六：查看版本号
    git log --pretty=oneline

十七:版本回滚
    git reset --hard 版本号

十八：回退到最新版本
    git reflog            