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

      $nazvy_sloupcu = array('nazev', 'styl', 'jmeno');
      $nadpisy_sloupcu = array('Název', 'Styl', 'Autor');

      //rc hudebnika poslano pres input hidden
      if(isset($_POST['rc_hud'])) {
        $rc_hud = $_POST['rc_hud'];
        header("Location:?rc_hud=$rc_hud"); //poslu rc hudebnika do get parametru
      }
      elseif (isset($_GET['rc_hud'])) {
        $rc_hud = $_GET['rc_hud'];
      }
      else {
        echo "Neni zadan konkretni hudebnik.";
        exit();
      }
      //idcka skladeb pro hudebnika
      if(isset($_POST['skladby'])) {
        $skladby = $_POST['skladby'];
        //pridani skladeb hudebnikovi
        foreach ($skladby as $i => $s) {
          $sql_prid_skl = "INSERT INTO Ma_nastudovano VALUES ('$rc_hud', '$s');";
          $prid_skl_vysl = mysql_query($sql_prid_skl);
          // echo $sql_prid_skl;
          // echo "<br>";
        }
      }

      $sql_jm_hud = "SELECT * FROM Hudebnik WHERE rodne_cislo='$rc_hud'";
      $hudebnik_vysledek = mysql_query($sql_jm_hud);
      $hudebnik_radek = mysql_fetch_array($hudebnik_vysledek);
      $jmeno = $hudebnik_radek['jmeno'];
      $prijmeni = $hudebnik_radek['prijmeni'];

      echo "<h1>Detail hudebnika $jmeno $prijmeni</h1>";

      $sql_sez_skl = "SELECT *
                      FROM Ma_nastudovano
                      NATURAL JOIN (
                        SELECT jmeno, styl, nazev, ID_skladby
                        FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE rodne_cislo='$rc_hud'";
      // echo $sql_sez_skl;
      $sez_skl_vysl = mysql_query($sql_sez_skl);
      $columns_count = count($nazvy_sloupcu);

      echo "<h3>Seznam nastudovanych skladeb</h3>";

      echo "<table>";
      echo "<tr>";
      foreach ($nadpisy_sloupcu as $nadpis) {echo "<td class=\"hlavicka\">$nadpis</td>";}
      echo "</tr>";

      while ($skladba = mysql_fetch_array($sez_skl_vysl)) {
        // echo "<br>";
        // print_r($skladba);
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
