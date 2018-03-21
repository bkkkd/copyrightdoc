<?php
/**
 * Short description for copyrightdoc.php
 *
 * @package copyrightdoc
 * @author tim <tim@tim-notebook>
 * @version 0.1
 * @copyright (C) 2018 tim <tim@tim-notebook>
 * @license MIT
 */
$short_opt = 'd:';
$short_opt .= 'o:';
$short_opt .= 'e:';
$short_opt .= 'x:';
$short_opt .= 'h';

$options = getopt($short_opt);

$help = false;
if(isset($options['h'])){
    $help = true;
}
if($help){
    help();
}

if(empty($options['d'])){
    help();
}
$dir = realpath($options['d']);
put('dir:'.$dir);

$exts = ['php','tpl'];
if(!empty($options['e'])){
    if(is_array($options['e'])){

        $exts = $options['e'];
    }elseif(is_string($options['e'])){
        $exts = [$options['e']];
    }


}
put('ext:'.implode(',', $exts));

$excludes = ['/.git/','/tmp/'];
if(!empty($options['x'])){
    if(is_array($options['x'])){
        $excludes = $options['x'];
    }elseif(is_string($options['x'])){
        $excludes = [$options['x']];
    }
}
put('excludes:'.implode(',', $excludes));

$outfile = basename($dir).'.docx';
if(!empty($options['o'])){
    $outfile = $options['o'];
}
put('outfile:'. $outfile);

function put($msg=""){
    fwrite(STDOUT, $msg."\n");
}
function help(){
    put('Help message');
    put('------------');
    put('copyright -d /your/path/to [-h] [-e tpl -e php] [-o projectname] [-x /.git/ -x /tmp/]');
    put('  -d set source code directory. request fill it.');
    put('  -o set a outfile name. default is project directory name.');
    put('  -e set a file extension. default: php and tpl');
    put('  -x set exclude string.default is /.git/ and /tmp/');
    put('  -h this message');
    exit;

}
function get_allfiles($path,&$files) {  
    if(is_dir($path)){  
        $dp = dir($path);  
        while ($file = $dp ->read()){  
            if($file !="." && $file !=".."){  
                get_allfiles($path."/".$file, $files);  
            }  
        }  
        $dp ->close();  
    }  
    if(is_file($path)){  
        $files[] =  $path;  
    }  
}  
$files = [];
get_allfiles($dir, $files);

include_once __DIR__.'/vendor/autoload.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$dir_lenght = strlen($dir);

foreach($files as $file){
    $jump = false;
    foreach($excludes as $ex){
        if(strpos($file, $ex)!==false){
            $jump = true;
            break;
        }
    }
    $ext = substr(strrchr(strtolower($file), '.'), 1);
    if(!in_array($ext, $exts)){
        $jump = true;
    }

    if($jump){
        continue;
    }
    $filepath = substr($file, $dir_lenght+1);
    put($filepath);
    $filedb = file($file);
    $section->addText('file path:'.trim(htmlspecialchars($filepath)));
    foreach($filedb as $line){
        if(!trim($line)){
            continue;
        }
        $section->addText(trim(htmlspecialchars($line)));
    }
}
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save($outfile);
