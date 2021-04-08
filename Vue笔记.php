<?php

一：Vue计算属性和方法的区别:
    计算属性因为Vue内部对该方法进行了缓存,只要属性值不发生改变,不管调用了多少次,该方法之执行一次。而方法是调用一次执行一次。

二:Vue组件使用注意事项:
    <body>
		<div id="app">
			<!-- 组件中不能使用驼峰方法,必须使用-分割 -->
			<cpn :child-moives="movies" :child-info="info"></cpn>
		</div>
		
		<template id="cpn">
			<!-- 如果在组件中有多行,应该在最外面包含一个div,要不然会报错 -->
			<div>
			    <p>{{childInfo}}</p>
				<ul>
					<li v-for="item in childMoives">{{item}}</li>
				</ul>
			</div>
		</template>
		
		<script src="../../js/vue.js"></script>
		
		<script>
			const app = new Vue({
				el:'#app',
				data:{
					movies: ['海贼王','海王','海尔兄弟'],
					info: 'hello vue'
				},
				components:{
					cpn:{
						template:'#cpn',
						props:{
							childMoives:{
								type:Array,
								//如果是对象或者数组,这里应该使用方法
								default(){
									return []
								},
								//必须在组件中传值
								required:true
							},
							childInfo:{
								type:String,
								default:''
							}
						},
						data(){
							return {}
						}
					}
				}
			})
		</script>
	</body>    
   
三:安装webpack
   1:npm install webpack@3.6.0 -g //全局安装webpack，版本3.6.0  
   2:npm install webpack@3.6.0 --save-dev //本地安装webpack开发环境,版本3.6.0  --save-dev:是开发时依赖，项目打包后不需要继续使用

四:loader的使用过程
   步骤一:通过npm安装需要使用的loader
   步骤二:在webpack.config.js中的module关键字下进行配置   

   1:安装css样式依赖
     npm install --save-dev css-loader
     npm install --save-dev style-loader

五:Vue Cli3(vue脚手架)的安装
   前提需要:1、安装node 2、安装webpack
   1、vue install @vue/cli -g //全局安装vue脚手架 
   2、vue create 项目名称 //vue安装项目    
   3、拉取2.x版本
     npm install @vue/cli-init -g
   4、安装2.x项目
     vue init webpack 项目名称
     
六:ES6中箭头函数的使用方法 
   1、当箭头函数中有多个参数时
     const sum = (num1,num2) => {
     	return num1 + num2
     }  
   2、当箭头函数只有一个参数时，小括号可以省略
     const sum = num => {
     	return num * num
     }

   3、当箭头函数只有一行返回值时
     const sum = (num1,num2) => num1 + num2  

七:解决：vue CLI 3 项目创建时,git bash 箭头选择无效问题     
   1、选择git bash 的安装目录，找到bash.bashrc文件
   2、文件末未添加:alias vue='winpty vue.cmd'
   3、重启git bash 即可

八：关闭eslint
    在config文件夹下的index.js中useEslint: false  

九：$router和$route的区别
    $route指的是当前活跃路由
    $router指的是注册的实例路由
    例如:
    const router = new VueRouter({
	  //配置路由和组件之间的应用关系
	  routes,
	  //路由显示模式，不写默认hash
	  mode:'history',
	  linkActiveClass:'active'
	})    

十：export和export default的区别
   相同点:
   1、export和export default都可以用于导出常量、函数、文件、模块等
   2、都可以通过import导入

   不同点:
   1、export可以有多个，export default只能有一个
   2、export方式导出，在导入时要加{},export default不需要
   3、使用export default为模块指定默认输出，导入时只需要知道文件名即可，但是使用export必须知道导出的变量或者函数等，导入时变量名要一致
   例如：
   //demo1.js
   export default const a = "hello world"
   导入方式
   import b from 'demo1.js' //这里的b可以是任何变量

   //demo2.js
   export const a = "hello world"
   导入方式
   import {a} from 'demo2.js' //这里的变量必须加{},而且必须跟导出的变量名一致

 十一：安装vuex状态管理工具
   npm install vuex --save  

 十二：安装axios
   npm install axios --save  

 十三：修改npm的源地址
      设置成淘宝源:npm config set registry https://registry.npm.taobao.org
      查看结果:npm config get registry
