<?php
$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xnovot0m', 'obaci4fa');
if (!$db) die('nelze se pripojit '.mysql_error());
if (!mysql_select_db('xnovot0m', $db)) die('database neni dostupna '.mysql_error());
echo "uspesne pripojeno\n";

$sql = "select * from Hudebnik";
$hudebnici = mysql_query($sql);

for ($i=0; $i < mysql_num_rows($hudebnici); $i++) { 
  $row = mysql_fetch_row($hudebnici);
  for ($j=0; $j < mysql_num_fields($hudebnici); $j++) { 
    echo $row[$j];
    echo "    ";
  }
  echo "<br>";
}
?>
