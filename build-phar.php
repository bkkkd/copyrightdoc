<?php
/**
 * Short description for build-phar.php
 *
 * @package build-phar
 * @author tim <tim@tim-notebook>
 * @version 0.1
 * @copyright (C) 2018 tim <tim@tim-notebook>
 * @license MIT
 */

// 参数内容为生成文件路径 此例中则在当前目录生成example.phar打包程序
$path = __DIR__.'/dist';
$filename = $path.'/copyrightdoc.phar';
if(!is_dir($path)){
    mkdir($path,0777);
}
if(file_exists($filename)){
    unlink($filename);
}
$phar = new Phar($filename);    
// 开始打包
$phar->startBuffering();  
// 要打包的目的目录 绝对路径
$phar->buildFromDirectory(__DIR__);  
// 压缩方式 GZ->gzip  BZ2->bz2
$phar->compressFiles(Phar::GZ);    
// 设置启动加载脚本 即导入phar之后第一个自动执行的脚本
$phar->setStub('#!/usr/bin/env php'.PHP_EOL.$phar->createDefaultStub('copyrightdoc.php'));  
// 结束打包
$phar->stopBuffering();
