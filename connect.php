<?php
include 'functions.php';
$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xhudzi01', 'ompur5an');
if (!$db) die('nelze se pripojit '.mysql_error());
if (!mysql_select_db('xhudzi01', $db)) die('database neni dostupna '.mysql_error());
user_db_query("SET CHARACTER SET latin2");
?>
