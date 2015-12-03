<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <!-- <script type="text/javascript" src="js/netteForms.js"></script> -->
    <!-- <script src="js/dateValidator.js"></script> -->
    <!-- <script src="js/libs/jquery-2.1.1.js"></script> -->
    <!-- <script src="js/filter.js"></script> -->
    <!-- <script src="js/form.js"></script> -->
    <style> .required label { color: maroon } </style>
    <title>Filharmonie Liptákov</title>
  </head>
  <body>
    <?php
      include "connect.php";

      if(isset($_GET['id_skl'])) {
        $id_skl = $_GET['id_skl'];
      }
      else {
        echo "Nebyla zadana skladba, ktere se maji priradit nastroje.";
        exit();
      }

      $sql_naz_skl = "SELECT nazev FROM Skladba WHERE ID_skladby='$id_skl'";
      $skladba_vysledek = mysql_query($sql_naz_skl);
      $skladba_radek = mysql_fetch_array($skladba_vysledek);
      $naz_skl = $skladba_radek['nazev'];
      echo "<h2>Vyberte nastroje pro skladbu $naz_skl</h2>";

      $nadpisy_sloupcu = array('Typ');
      $nazvy_sloupcu = array('ttype');
      $col_count = count($nazvy_sloupcu);
      $typ_nas = 'ttype';

      $sql_typy = "SELECT ttype FROM Nastroj GROUP BY ttype";  //FIXME: vypisovat jenom typy(zgrupovane)
      $typy = mysql_query($sql_typy);
      echo "<form action='skladba.php' method='post'>";
      echo "<table>";
      while($row_typ = mysql_fetch_array($typy)) {
        echo "<tr>";
        for($i=0; $i < $col_count; $i++) {
          echo "<td>$row_typ[$i]</td>";
        }
        echo "<td><input type='number' name='$row_typ[$typ_nas]' value='0' min='0'></td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "<input type='hidden' name='id_skl' value='$id_skl'>";
      echo "<input type='submit' name='nastr_odeslat' value='Vybrat nastroje'>";
      echo "</form>";

    ?>
  </body>
</html>
