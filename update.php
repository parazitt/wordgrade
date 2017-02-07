<?php
set_time_limit (5 * 60);

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
function download($url, $dst = "."){
    $destination_folder = './';
    $target = $dst . '/' . basename($url);
    $remote = fopen ($url, "rb");
    if (!$remote) 
        return false;
    $local = fopen ($target, "wb");
    chmod($newfname,755);
    if (!$newf)
        return false;
    while(!feof($remote)) {
        fwrite($local, fread($remote, 1024 * 8 ), 1024 * 8 );
    }
    return $target;
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

$wp = download("https://fa.wordpress.org/wordpress-4.7.2-fa_IR.zip");
unzip($wp, "./");

foreach($files as $file){
    cpRf("wordpress/".$file, "./".$file);
}

rmRf("wordpress");
header("Location:  /wp-admin/upgrade.php?_wp_http_referer=%2Fwp-admin%2Fupdate-core.php");
