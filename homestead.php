---
ip: "192.168.10.10"
memory: 2048
cpus: 1
provider: virtualbox

authorize: ~/.ssh/id_rsa.pub

keys:
    - ~/.ssh/id_rsa

folders:
    - map: D:Code
      to: /home/vagrant/Code


sites: 

    - map: mingzheng.test
      to: /home/vagrant/Code/mingzheng/public      

    - map: hsshop.test
      to: /home/vagrant/Code/hsshop/public 
    
    - map: blog.test
      to: /home/vagrant/Code/blog/public

    - map: shop.test
      to: /home/vagrant/Code/laravel-shop/public 

    - map: think5.1.test
      to: /home/vagrant/Code/think5.1/public 
      type: thinkphp

    - map: laravel6.test
      to: /home/vagrant/Code/laravel6/public         

    - map: hsy.test
      to: /home/vagrant/Code/hsy/public  

    - map: demo.test
      to: /home/vagrant/Code/demo/public

    - map: swoole.test
      to: /home/vagrant/Code/swoole/
      
databases:
    - mingzheng
    - dream_shop
    - blog
    - laravel-shop
    - think5.1
    - laravel6
    - hsy
    - demo
   
# blackfire:
#     - id: foo
#       token: bar
#       client-id: foo
#       client-token: bar

# ports:
#     - send: 50000
#       to: 5000
#     - send: 7777
#       to: 777
#       protocol: udp












# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
# 127.0.0.1       localhost
# ::1             localhost

0.0.0.0 account.jetbrains.com
192.168.10.10   hsshop.test
192.168.10.10   mingzheng.test
192.168.10.10   blog.test
192.168.10.10   shop.test
192.168.10.10   laravel6.test
192.168.10.10   think5.1.test
192.168.10.10   hsy.test
192.168.10.10   demo.test
192.168.10.10   swoole.test


127.0.0.1  hyerp.test
127.0.0.1 liubei.com
127.0.0.1 erp.test
127.0.0.1 laravelDemo.com
127.0.0.1 sx_bbc.com
127.0.0.1 hsshop.com
127.0.0.1 tpshop.test

