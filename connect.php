<?php
$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xnovot0m', 'obaci4fa');
if (!$db) die('nelze se pripojit '.mysql_error());
if (!mysql_select_db('xnovot0m', $db)) die('database neni dostupna '.mysql_error());

?>
