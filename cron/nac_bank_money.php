<?php

$xml = file_get_contents("https://nationalbank.kz/rss/rates_all.xml");
file_put_contents("/www/wwwroot/newshub.kz/public/assets/rates_all.xml", $xml); 

?>