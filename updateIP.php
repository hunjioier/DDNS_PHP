<?php
    $config=file_get_contents("/home/a/config");
    $conf_arr=json_decode($config,true);
    $LOG=$conf_arr["log"];
    $IP_TEMP=$conf_arr["ip_temp"];

    $tempIpAdress=file_get_contents("http://ip-api.com/json");
    $arr=json_decode($tempIpAdress,true);
    //var_dump($arr);
    //die;

    $ip=$arr["query"];
    if($ip=="")
    {
        $content=date("Y-m-d h:i:s")." get ip faild\n";
        file_put_contents($LOG."updateIP".date("Ymd").".log.wf", $content,FILE_APPEND);
        exit(1);
    }
    if(file_exists($IP_TEMP)==true)
    {
        $hander=fopen($IP_TEMP,"r");
        $tempip=fgets($hander);
        if($tempip===$ip)
        {
            fclose($hander);
            $content=date("Y-m-d h:i:s")." ip not change ".$ip."\n";
            file_put_contents($LOG."updateIP".date("Ymd").".log", $content,FILE_APPEND);
        }
        else
        {
            fclose($hander);
            $hander=fopen($IP_TEMP,"w");
            fwrite($hander,$ip);
            fclose($hander);
            $content=date("Y-m-d h:i:s")." ip from ".$tempip." change to ".$ip." prepare to change A record\n";
            file_put_contents($LOG."updateIP".date("Ymd").".log", $content,FILE_APPEND);
            updateARecord();
        }
    }
    else
    {
        $hander=fopen($IP_TEMP,"w");
        fwrite($hander,$ip);
        fclose($hander);
        $content=date("Y-m-d h:i:s")." set up ip_config ip is ".$ip." prepare to change A record \n";
        file_put_contents($LOG."updateIP".date("Ymd").".log", $content,FILE_APPEND);
        updateARecord();
    }
    function updateARecord()
    {
        //todo
    }
?>
