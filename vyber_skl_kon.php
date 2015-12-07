<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <!-- <script type="text/javascript" src="js/netteForms.js"></script> -->
    <!-- <script src="js/dateValidator.js"></script> -->
     <script src="js/libs/jquery-2.1.1.js"></script> 
    <!-- <script src="js/filter.js"></script> -->
     <script src="js/form.js"></script>
    <style> .required label { color: maroon } </style>
    <title>Filharmonie Liptákov</title>
  </head>
  <body>
    <?php
      include "connect.php";
      include 'functions.php';

      if(isset($_GET['id_kon'])) {
        $id_kon = $_GET['id_kon'];
      }
      else {
        echo "Nebyl zadan koncert, ke kteremu se maji priradit skladby.\n";
        exit();
      }

     if (isset($_POST["edit"]) and $_POST["edit"]="true") {
        $sql = "SELECT MAX(poradi)
                FROM Slozen_z
                WHERE ID_koncertu = $id_kon;";
        $poradi = mysql_fetch_array(user_db_query($sql));
        $poradi = $poradi[0];
        $poradi++;

        $sql = "SELECT COUNT(ID_skladby) FROM Skladba";
        $pocet = mysql_fetch_array(user_db_query($sql));
        $pocet = $pocet[0];

         for ($i=0; $i < $pocet; $i++) { 
           if (isset($_POST[$i])) {
            $sql = "INSERT INTO Slozen_z
                    VALUES ($id_kon, {$_POST[$i]}, $poradi)";
            user_db_query($sql);
            $poradi++;
           }
           
         }

      }

      if (isset($_GET["delete"])) {
        $sql = "DELETE FROM Slozen_z
                WHERE ID_koncertu = {$_GET["id_kon"]} and ID_skladby = {$_GET["delete"]};";
        user_db_query($sql);
      }
      $sql = "SELECT poradi, nazev, delka, ID_skladby
              FROM Slozen_z NATURAL JOIN Skladba
              WHERE ID_koncertu = $id_kon
              ORDER BY poradi";
       $title="Skladby koncertu";
       $nadpisy_sloupcu = array("Pořadí", "Název", "Délka");
       $nazvy_sloupcu = array('poradi', 'nazev', 'delka');
       $columns_count = count($nazvy_sloupcu);
       $table = user_db_query($sql);
       $PK = "ID_skladby";

       echo "<div>\n";
       echo "<h3>$title</h3>\n";

      echo "<table class=\"data\">\n";
        //výpis hlavičky tabulky
      for ($i=0; $i <$columns_count ; $i++) { 
        echo "<td class='filter_{$nadpisy_sloupcu[$i]}'>$nadpisy_sloupcu[$i]</td>\n";
      }

        //výpis těla
      while ($row = mysql_fetch_array($table, MYSQL_ASSOC)) {
        echo "<tr>\n";
        for ($i=0; $i <count($nazvy_sloupcu); $i++) {
          if ($nazvy_sloupcu[$i] == "nazev") 
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='skladba.php?id_skl={$row["ID_skladby"]}'>{$row[$nazvy_sloupcu[$i]]}</a></td>\n";
          else
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>\n";
        }
        echo "<td id=delete_btn><a href='?delete={$row[$PK]}&id_kon=$id_kon'>Odstranit</a></td>\n";
      }
      echo "</table>\n";
      echo "</div>\n";

      echo "<button onclick='show_table()'>Pridat skladby</button>\n";
    /***********************************
      VYPIS PRIDAVANI DALSICH SKLADEB
    ************************************/       
      $sql = "SELECT DISTINCT nazev, jmeno, delka, ID_skladby
              FROM Skladba NATURAL JOIN Autor";

      $title = "Tabulky pro pridani";
      $nazvy_sloupcu = array("nazev", "jmeno", "delka");
      $nadpisy_sloupcu = array("Přidat", "Název", "Jméno", "Délka [min]");
      $columns_count = count($nadpisy_sloupcu);
      $table = user_db_query($sql);
      $PK = "ID_skladby";

      echo "<div class='add'>\n";
       echo "<h3>$title</h3>\n";

      echo "<form action='vyber_skl_kon.php?id_kon=$id_kon' method='post'>";
      echo "<table class=\"data\" class=\"\">\n";
        //výpis hlavičky tabulky
      for ($i=0; $i <$columns_count ; $i++) { 
        echo "<td class='filter_{$nadpisy_sloupcu[$i]}'>$nadpisy_sloupcu[$i]</td>\n";
      }
        //výpis těla
      $row_count=0;
      while ($row = mysql_fetch_array($table, MYSQL_ASSOC)) {
        echo "<tr>\n";

        echo "<td><input type='checkbox' name='$row_count' value={$row["ID_skladby"]}></td>\n";
        for ($i=0; $i <count($nazvy_sloupcu); $i++) {
          if ($nazvy_sloupcu[$i] == "nazev") {
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='skladba.php?id_skl={$row["ID_skladby"]}'>{$row[$nazvy_sloupcu[$i]]}</a></td>\n";
            continue;
          }
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>\n";
        }
        $row_count++;
      }
      echo "<button onclick=''>Pridat</button>\n";
      echo "</table>\n";
      echo "<input type='hidden' name='edit' value='true'>";
      echo "</form>";
      echo "</div>\n";


    ?>
  </body>
  <script type="text/javascript">
       //pri obnoveni stranky
       $('.add').hide();
  </script>    
</html>
