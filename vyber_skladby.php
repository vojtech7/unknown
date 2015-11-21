<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <script type="text/javascript" src="js/netteForms.js"></script>
    <script src="js/dateValidator.js"></script>
    <script src="js/libs/jquery-2.1.1.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <style> .required label { color: maroon } </style>
    <title>Nástrojáø Filharmonie Liptákov</title>
  </head>
  <body>
    <?php
      include "connect.php";

      $nazvy_sloupcu_aut = array('jmeno', 'styl');
      $nazvy_sloupcu_skl = array('nazev', 'delka');
      $col_count_aut = count($nazvy_sloupcu_aut);
      $col_count_skl = count($nazvy_sloupcu_skl);

      $sql_aut = "SELECT ID_autora, jmeno, styl FROM Autor";
      $autori = mysql_query($sql_aut);
      $sql_skl_zaklad = "SELECT nazev, delka, ID_autora FROM Skladba WHERE ID_autora=";

      while($row_aut = mysql_fetch_array($autori)){
        echo "<table><tr>";
        for ($i=1; $i <= $col_count_aut; $i++) {
          echo "<td>{$row_aut[$i]}</td>";
        }
        echo "</tr></table>";
        $ID_aut = $row_aut[0];
          
        $sql_skl = $sql_skl_zaklad."'$ID_aut'";
        $skladby = mysql_query($sql_skl);

        echo "<table style='margin-left:20px;'>";
        while($row_skl = mysql_fetch_array($skladby)){
          echo "<tr>";
          for ($i=0; $i < $col_count_skl; $i++) {
            echo "<td>{$row_skl[$i]}</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
      }


    ?>
  </body>
</html>
