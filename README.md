# 开发理由 #
在申请软件著作权时,需要提供源代码打印件,如果手工编辑,控制出错,而且更新也不方便,所以就写了一个代码把这个工作变成一个命令

# 快速使用 #
先要确认已经安装了php程序

    php copyrightdoc.phar -d /path/to/project -x /.git/ -x /tmp/ -x /vendors/ -e php -e tpl 

即会在当前目录下生成project.docx文档

# 源码应用 # 
配置环境

    git clone https://github.com/bkkkd/copyrightdoc.git
    composer install

使用源码执行

    php copyrightdoc.php -d /path/to/project -x /.git/ -x /tmp/ -x /vendors/ -e php -e tpl 

# 编译成phar #
在源码应用可以执行的基础上,只要执行以下命令,即可生成 copyrightdoc.phar包

    php build-doc.php

将会在dist目录下创建 copyrightdoc.phar 包

# 其它说明 #
在linux环境下,
可以把copyrightdoc.phar 复制到 /usr/local/bin/copyrightdoc 
以后就可以直接 copyrightdoc 命令了
