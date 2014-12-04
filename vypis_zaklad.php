<?php
$db = mysql_connect('localhost:/var/run/mysql/mysql.sock', 'xnovot0m', 'obaci4fa');
if (!$db) die('nelze se pripojit '.mysql_error());
if (!mysql_select_db('xnovot0m', $db)) die('database neni dostupna '.mysql_error());
echo "uspesne pripojeno\n";
?>


<?php
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


<?php
                       $query = "select * from Hudebnik";
                        
                       $r = mysql_query($query);
                        
                        while($row = mysql_fetch_array($r)){
                            echo "<tr>
                                    <td class='filter_rodne_cislo'>{$row['rodne_cislo']}</td>
                                    <td class='filter_jmeno'>{$row['jmeno']}</td>
                                    <td class='filter_prijmeni'>{$row['prijmeni']}</td>";
                                    /*********************************
                                    if($row['na_predpis']) echo "<td class='filter_predpis'>ano</td>";
                                    else echo "<td class='filter_predpis'>ne</td>
                          
                                    <form method='post'><td class='btn_pridavani'><input type='number' size='4' min='1' name='mnozstvi'></td>
                                    <td class='btn_objednat'><input type='hidden' value='{$row['id_lek']}' name='objednavka'><input type='hidden' value='{$row['id_dodavatel']}' name='dodavatel'><button class='btn btn-primary' type='submit'>Objednat léky</button></td></form>
                                    <td class='btn_odebrat'><button class='btn btn-danger' value='{$row['id_lek']}' onclick='document.getElementById(\"mazanec\").value = this.value;' data-toggle='modal' data-target='#myModal2' type='button'>Odebrat léky</button></td>
                                  ***********************/
                            echo "</tr>";                            
                        }          
                    ?>
