<?php

function rmRf($path){
    if(is_file($path)){
        unlink($path);
        return;
    }
    $dir = opendir($path);
    while (($item = readdir($dir))){
        if($item == "." || $item == "..")
            continue;
        if(is_file($path."/".$item)){
            unlink($path."/".$item);
        }else{
            rmRf($path."/".$item);
        }
    }
    closedir($dir);
    rmdir($path);
}
function cpRf($src, $dst){
    if(is_file($src))
        copy($src, $dst);
    else{
        $dir = opendir($src);
        @mkdir($dst);
        while(($item = readdir($dir))){
            if($item == "." || $item == "..")
                continue;
            if(is_file($src."/".$item))
                copy($src."/".$item, $dst."/".$item);
            else
                cpRf($src."/".$item,$dst."/".$item);
        }
        closedir($dir);

    }   
}
function unzip($archive, $dst){
    $zip = new ZipArchive;
    $res = $zip->open($archive);
    if($res){
        $zip->extractTo($dst);
        $zip->close();
    }
}


$files = ['wp-includes', 'wp-comments-post.php', 'wp-signup.php',
          'wp-login.php', 'wp-blog-header.php', 'wp-config-sample.php',
          'xmlrpc.php', 'license.txt', 'wp-load.php', 'wp-mail.php',
          'wp-admin', 'wp-cron.php', 'index.php', 'wp-trackback.php',
          'wp-settings.php',  'wp-links-opml.php', 'wp-activate.php',
          "wp-content/languages"];



foreach ($files as $file){
    rmRf($file);
}

unzip("wordpress-4.7.2-fa_IR.zip", "./");

foreach($files as $file){
    cpRf("wordpress/".$file, "./".$file);
}

rmRf("wordpress");
