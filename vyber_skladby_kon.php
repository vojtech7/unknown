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

      if(isset($_GET['id_kon'])) {
        $id_kon = $_GET['id_kon'];
      }
      else {
        echo "Nebyl zadan koncert, ke kteremu se maji priradit skladby.";
        exit();
      }

      $nazvy_sloupcu_aut = array('jmeno', 'styl');
      $nazvy_sloupcu_skl = array('nazev', 'delka');
      $col_count_aut = count($nazvy_sloupcu_aut);
      $col_count_skl = count($nazvy_sloupcu_skl);

      $sql_aut = "SELECT ID_autora, jmeno, styl FROM Autor";
      $autori = mysql_query($sql_aut);
      $sql_skl_zaklad = "SELECT ID_skladby, nazev, delka, ID_autora FROM Skladba WHERE ID_autora=";

      $sql_naz_kon = "SELECT nazev_koncertu FROM Koncert WHERE ID_koncertu='$id_kon'";
      $koncert_vysledek = mysql_query($sql_naz_kon);
      $koncert = mysql_fetch_array($koncert_vysledek);
      $nazev_koncertu = $koncert['nazev_koncertu'];
      echo "<h2>Vyberte skladby pro koncert $nazev_koncertu</h2>";

      echo "<form action='koncert.php' method='post'>";
      while($row_aut = mysql_fetch_array($autori)){
        echo "<table><tr>";
        //vypis autora
        for ($i=1; $i <= $col_count_aut; $i++) {
          echo "<td>{$row_aut[$i]}</td>";
        }
        echo "</tr></table>";
        $ID_aut = $row_aut[0];
          
        $sql_skl = $sql_skl_zaklad."'$ID_aut'";
        $skladby = mysql_query($sql_skl);

        //vypis skladeb daneho autora
        echo "<table style='margin-left:20px;'>";
        while($row_skl = mysql_fetch_array($skladby)){
          echo "<tr>";
          echo "<td><input type='checkbox' name='skladby[]' value='$row_skl[0]'></td>";
          for ($i=1; $i < $col_count_skl; $i++) {
            echo "<td>{$row_skl[$i]}</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
      }
      echo "<input type='hidden' name='id_kon' value='$id_kon'>";
      echo "<input type='submit' name='skl_odeslat' value='Vybrat skladby'>";
      echo "</form>";

    ?>
  </body>
</html>
