<?php
    $config=file_get_contents("/home/a/config");
    $conf_arr=json_decode($config,true);
    $LOG=$conf_arr["log"];
    $IP_TEMP=$conf_arr["ip_temp"];
    $SSH_KEY=$conf_arr["ssh_key"];
    $SSH_PWD=$conf_arr["ssh_pwd"];
    $DOMAIN=$conf_arr["domain"];
    $NAME=$conf_arr["name"];
    $TTL=$conf_arr["ttl"];

    $tempIpAdress=file_get_contents("http://ip-api.com/json");
    $arr=json_decode($tempIpAdress,true);

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
            updateARecord($ip,$SSH_KEY,$SSH_PWD,$DOMAIN,$NAME,$TTL,$LOG);
        }
    }
    else
    {
        $hander=fopen($IP_TEMP,"w");
        fwrite($hander,$ip);
        fclose($hander);
        $content=date("Y-m-d h:i:s")." set up ip_config ip is ".$ip." prepare to change A record \n";
        file_put_contents($LOG."updateIP".date("Ymd").".log", $content,FILE_APPEND);
        updateARecord($ip,$SSH_KEY,$SSH_PWD,$DOMAIN,$NAME,$TTL,$LOG);
    }
    function updateARecord($ip,$SSH_KEY,$SSH_PWD,$DOMAIN,$NAME,$TTL,$LOG)
    {
        $cmd="curl -i -s -X PUT -H \"Authorization: sso-key ".$SSH_KEY.":".$SSH_PWD."\" -H \"Content-Type: application/json\" -d '[{\"data\":\"'".$ip."'\",\"ttl\":".$TTL."}]' \"https://api.godaddy.com/v1/domains/".$DOMAIN."/records/A/".$NAME."\"";
        $info="";
        exec($cmd,$info);
        if($info[0]=="HTTP/1.1 200 OK")
        {
            $content=date("Y-m-d h:i:s")." ip update success ".$ip."\n";
            file_put_contents($LOG."updateIP".date("Ymd").".log", $content,FILE_APPEND);
        }
        else
        {
            $content=date("Y-m-d h:i:s")." ip update error\n ".$ip.implode(" ",$info)."\n";
            file_put_contents($LOG."updateIP".date("Ymd").".log.wf", $content,FILE_APPEND);
        }
    }
?>
