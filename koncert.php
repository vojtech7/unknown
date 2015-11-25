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
    <title>Filharmonie Liptákov</title>
  </head>
  <body>
    <?php
      include "connect.php";

      
      $nazvy_sloupcu = array('ID_skladby', 'ID_koncertu', 'poradi', 'jmeno', 'styl', 'nazev', 'delka');

      //id koncertu poslano pres input hidden
      if(isset($_POST['id_kon'])) {
        $id_kon = $_POST['id_kon'];
        print_r($id_kon);
        echo "<br>";
      }
      elseif (isset($_GET['id_kon'])) {
        $id_kon = $_GET['id_kon'];
      }
      else {
        echo "Neni zadan konkretni koncert.";
        exit();
      }
      //idcka skladeb pro koncert
      if(isset($_POST['skladby'])) {
        $skladby = $_POST['skladby'];
        print_r($skladby);
        echo "<br>";
      }
      else {
        echo "Nejsou zadany skladby pro koncert.";
        exit();
      }

      $sql_naz_kon = "SELECT * FROM Koncert WHERE ID_koncertu='$id_kon'";
      $koncert_vysledek = mysql_query($sql_naz_kon);
      $koncert = mysql_fetch_array($koncert_vysledek);
      $nazev =  $koncert['nazev_koncertu'];
      $datum =  $koncert['datum_a_cas'];
      $mesto =  $koncert['mesto'];
      $adresa = $koncert['adresa'];
      print_r($koncert);  //FIXME: zobrazit privetiveji, nez takto
      echo "<br>";

      //pridani skladeb koncertu
      foreach ($skladby as $i => $s) {
        $p = $i + 1;
        $sql_prid_skl = "INSERT INTO Slozen_z VALUES ('$id_kon', '$s', '$p');";
        $prid_skl_vysl = mysql_query($sql_prid_skl);
        echo $sql_prid_skl;
        echo "<br>";
      }

      $sql_sez_skl = "SELECT *
                      FROM Slozen_z
                      NATURAL JOIN (
                        SELECT jmeno, styl, nazev, delka, ID_skladby
                        FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE ID_koncertu =$id_kon
                      ORDER BY poradi ASC";
      echo $sql_sez_skl;
      $sez_skl_vysl = mysql_query($sql_sez_skl);
      $columns_count = count($nazvy_sloupcu);

      while ($skladba = mysql_fetch_array($sez_skl_vysl)) {
        //vypsat skladby jako u aranzera...
        echo "<br>";
        //print_r($skladba);
        echo $columns_count;
        echo "<tr>";
        for ($i=0; $i < $columns_count; $i++) {
          if($i==0) continue;
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$skladba[$i]}</td>";
        }
      }

    ?>
  </body>
</html>
