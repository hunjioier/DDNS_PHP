<?php
    $config=file_get_contents("/home/a/config");
    $conf_arr=json_decode($config,true);
    $LOG=$conf_arr["log"];
    $savetime=intval($conf_arr["log_saveTime"]);
    $dh=opendir($LOG);
    while($file=readdir($dh))
    {
        if($file=="." || $file=="..")
            continue;
        if((time()-filemtime($LOG.$file))>$savetime)
        {
            unlink($LOG.$file);
        }
    }
    closedir($dh);
?>
