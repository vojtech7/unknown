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

      if(isset($_GET['id_kon'])) {
        $id_kon = $_GET['id_kon'];
      }
      else {
        echo "Nebyl zadan koncert, ke kteremu se maji priradit skladby.\n";
        exit();
      }

      $sql = "SELECT poradi, nazev, delka, ID_skladby
              FROM Slozen_z NATURAL JOIN Skladba
              WHERE ID_koncertu = 16
              ORDER BY poradi";
       $title="Skladby koncertu";
       $nadpisy_sloupcu = array("Pořadí", "Název", "Délka");
       $nazvy_sloupcu = array('poradi', 'nazev', 'delka');
       $columns_count = count($nazvy_sloupcu);
       $table = mysql_query($sql);
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
        echo "<td id=delete_btn><a href='?delete={$row[$PK]}'>Odstranit</a></td>\n";
      }
      echo "</table>\n";
      echo "</div>\n";

      echo "<button onclick='show_table()'>Pridat skladby</button>\n";
    /***********************************
      VYPIS PRIDAVANI DALSICH SKLADEB
    ************************************/       
      $sql = "SELECT DISTINCT nazev, jmeno, delka, styl, ID_skladby
              FROM Skladba
              NATURAL JOIN Autor
              NATURAL JOIN Slozen_z
              WHERE ID_koncertu != $id_kon";

      $title = "Tabulky pro pridani";
      $nazvy_sloupcu = array("nazev", "jmeno", "delka", "styl");
      $nadpisy_sloupcu = array("Přidat", "Název", "Jméno", "Délka [min]", "Styl");
      $columns_count = count($nadpisy_sloupcu);
      $table = mysql_query($sql);
      $PK = "ID_skladby";

      echo "<div class='add'>\n";
       echo "<h3>$title</h3>\n";

      echo "<form action='vyber_skl_kon.php' method='post'>";
      echo "<table class=\"data\" class=\"\">\n";
        //výpis hlavičky tabulky
      for ($i=0; $i <$columns_count ; $i++) { 
        echo "<td class='filter_{$nadpisy_sloupcu[$i]}'>$nadpisy_sloupcu[$i]</td>\n";
      }
      echo "string";
        //výpis těla
      while ($row = mysql_fetch_array($table, MYSQL_ASSOC)) {
        echo "<tr>\n";

        echo "<td><input type='checkbox' name={$row["ID_skladby"]}></td>\n";
        for ($i=0; $i <count($nazvy_sloupcu); $i++) {
          if ($nazvy_sloupcu[$i] == "nazev") {
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='skladba.php?id_skl={$row["ID_skladby"]}'>{$row[$nazvy_sloupcu[$i]]}</a></td>\n";
            continue;
          }
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>\n";
        }
      }
      echo "<button onclick=''>Pridat</button>\n";
      echo "</table>\n";
      echo "<form action='vyber_skl_kon.php' method='post'>";
      echo "</div>\n";


    ?>
  </body>
  <script type="text/javascript">
       //pri obnoveni stranky
       $('.add').hide();
  </script>    
</html>
