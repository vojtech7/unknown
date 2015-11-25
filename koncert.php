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
      
      $nazvy_sloupcu = array('nazev', 'styl', 'jmeno', 'delka', 'poradi');
      $nadpisy_sloupcu = array('Název', 'Styl', 'Autor', 'Délka [min]', 'Pořadí');

      //id koncertu poslano pres input hidden hned po zadani skladeb koncertu
      if(isset($_POST['id_kon'])) {
        $id_kon = $_POST['id_kon'];
        header("Location:?id_kon=$id_kon"); //poslu id koncertu do get parametru
      }
      //id koncertu poslano pres get, z odkazu ve vypisu koncertu
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
        //pridani skladeb koncertu
        foreach ($skladby as $i => $s) {
          $p = $i + 1;
          $sql_prid_skl = "INSERT INTO Slozen_z VALUES ('$id_kon', '$s', '$p');";
          $prid_skl_vysl = mysql_query($sql_prid_skl);
        }
      }

      $sql_naz_kon = "SELECT * FROM Koncert WHERE ID_koncertu='$id_kon'";
      $koncert_vysledek = mysql_query($sql_naz_kon);
      $koncert = mysql_fetch_array($koncert_vysledek);
      $nazev =  $koncert['nazev_koncertu'];
      $datum = date_create($koncert['datum_a_cas']);
      $datum = date_format($datum, "d.m.Y H:i");
      $mesto =  $koncert['mesto'];
      $adresa = $koncert['adresa'];

      echo "<h1>Detail koncertu $nazev</h1>";

      echo "<br><ul>
                  <li>Datum: $datum</li>
                  <li>Město: $mesto</li>
                  <li>Adresa: $adresa</li>
                </ul> ";

      $sql_sez_skl = "SELECT *
                      FROM Slozen_z
                      NATURAL JOIN (
                        SELECT jmeno, styl, nazev, delka, ID_skladby
                        FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE ID_koncertu =$id_kon
                      ORDER BY poradi ASC";
      // echo $sql_sez_skl;
      $sez_skl_vysl = mysql_query($sql_sez_skl);
      $columns_count = count($nazvy_sloupcu);

      echo "<h3>Seznam skladeb</h3>";

      echo "<table>";
      echo "<tr>";
      foreach ($nadpisy_sloupcu as $nadpis) {echo "<td class=\"hlavicka\">$nadpis</td>";}
      echo "</tr>";

      while ($skladba = mysql_fetch_array($sez_skl_vysl)) {
        // echo "<br>";
        //print_r($skladba);
        echo "<tr>";
        for ($i=0; $i < $columns_count; $i++) {
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$skladba[$nazvy_sloupcu[$i]]}</td>";
        }
      }
      echo "</tr>";
      echo "</table>";

    ?>
  </body>
</html>
