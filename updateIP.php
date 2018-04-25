<?php
    $value=@$_POST["pass"];
    if($value=="")
        exit;
	if($value==="testDDNS")
	{
		$ip=$_SERVER["REMOTE_ADDR"];
		if(file_exists("/var/www/html/ip_conf")==true)
		{
			$hander=fopen("/var/www/html/ip_conf","r");
			$tempip=fgets($hander);
			if($tempip===$ip)
			{
				fclose($hander);

				echo "toule";
			}
			else
			{
				fclose($hander);
				$hander=fopen("/var/www/html/ip_conf","w");
				fwrite($hander,$ip);
				fclose($hander);

				echo "gengxin";
				//todo
			}
		}
		else
		{
			$hander=fopen("/var/www/html/ip_conf","w");
			fwrite($hander,$ip);
			fclose($hander);

			echo "xinjian";
		}
	}
?>
