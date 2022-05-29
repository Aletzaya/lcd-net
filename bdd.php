<?php
try
{
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=u938386532_lcd;charset=utf8', 'u938386532_root', 'Lcd9623299');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}
