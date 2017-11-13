<?php
/*
 * set var
 */
$cfHost = "localhost";
$cfUser = "root";
$cfPassword = "A$192dijd";
$cfDatabase = "fai_fac";
/*
 * connection mysql
 */
$meConnect = mysql_connect($cfHost, $cfUser, $cfPassword) or die("Error conncetion mysql...");
$meDatabase = mysql_select_db($cfDatabase);
mysql_query("SET NAMES UTF8");
mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");
//mysql_query("SET character_set_results=utf8");
//mysql_query("SET character_set_client=utf8");
//mysql_query("SET character_set_connection=utf8");

set_time_limit(0);   
ini_set('mysql.connect_timeout','0');   
ini_set('max_execution_time', '0'); 
?>