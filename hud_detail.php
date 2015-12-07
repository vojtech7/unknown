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
      include 'functions.php';

      $nazvy_sloupcu_skl = array('nazev', 'jmeno', 'styl');
      $nadpisy_sloupcu_skl = array('Název', 'Autor', 'Styl');
      $nadpisy_sloupcu_nastr = array( 'Typ', 'Výrobce', 'Výrobní èíslo');
      $nazvy_sloupcu_nastr = array('ttype', 'vyrobce','vyrobni_cislo');

      //rc hudebnika poslano pres input hidden
      if(isset($_POST['rc_hud'])) {
        $rc_hud = $_POST['rc_hud'];
      }
      elseif (isset($_GET['rc_hud'])) {
        $rc_hud = $_GET['rc_hud'];
      }
      else {
        echo "Neni zadan konkretni hudebnik.";
        exit();
      }
          //odstranim vsechny zaznamy o upravovanem hraci (zapisou se nove)
      $sql_dlt = "DELETE FROM Ma_nastudovano
                  WHERE rodne_cislo = '$rc_hud'";
      if (user_db_query($sql_dlt) == false) {
        echo $sql_dlt;
      }

      //idcka skladeb pro hudebnika
      if(isset($_POST['skladby'])) {
        $skladby = $_POST['skladby'];
        //pridani skladeb hudebnikovi
        foreach ($skladby as $i => $s) {
          $sql_prid_skl = "INSERT INTO Ma_nastudovano VALUES ('$rc_hud', '$s');";
          $prid_skl_vysl = user_db_query($sql_prid_skl);
        }
      }
      
      $sql_jm_hud = "SELECT * FROM Hudebnik WHERE rodne_cislo='$rc_hud'";
      $hudebnik_vysledek = user_db_query($sql_jm_hud);
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
      $sez_skl_vysl = user_db_query($sql_sez_skl);
      $columns_count_skl = count($nazvy_sloupcu_skl);

      echo "<h3>Seznam nastudovanych skladeb</h3>";
      echo "<table>";
      echo "<tr>";
      foreach($nadpisy_sloupcu_skl as $nadpis_skl) {echo "<td class=\"hlavicka\">$nadpis_skl</td>";}
      echo "</tr>";

      while ($skladba = mysql_fetch_array($sez_skl_vysl)) {
        echo "<tr>";
        for ($i=0; $i < $columns_count_skl; $i++) {
          echo "<td class='filter_{$nazvy_sloupcu_skl[$i]}'>{$skladba[$nazvy_sloupcu_skl[$i]]}</td>";
        }
      }
      echo "</tr>";
      echo "</table>";
      echo "<a href=vyber_skladby_hud.php?rc_hud=$rc_hud&edit=true><label>Upravit</label></a>";


      $sql_sez_nastr = "SELECT *
                      FROM Nastroj
                      WHERE rodne_cislo='$rc_hud'";
      // echo $sql_sez_nastr;
      $sez_nastr_vysl = user_db_query($sql_sez_nastr);
      $columns_count_nastr = count($nazvy_sloupcu_nastr);

      echo "<h3>Seznam vypujcenych nastroju</h3>";
      echo "<table>";
      echo "<tr>";
      foreach($nadpisy_sloupcu_nastr as $nadpis_nastr) {echo "<td class=\"hlavicka\">$nadpis_nastr</td>";}
      echo "</tr>";

      while ($nastroj = mysql_fetch_array($sez_nastr_vysl)) {
        // echo "<br>";
        // print_r($nastroj);
        echo "<tr>";
        for ($i=0; $i < $columns_count_nastr; $i++) {
          echo "<td class='filter_{$nazvy_sloupcu_nastr[$i]}'>{$nastroj[$nazvy_sloupcu_nastr[$i]]}</td>";
        }
      }
      echo "</tr>";
      echo "</table>";
      echo "<a href=vyber_nastroje_hud.php?rc_hud=$rc_hud&edit=true><label>Upravit</label></a><br><br>";
      echo "<a href='index.php?page=personalista.php'>Zpet na vypis hudebniku</a>";

    ?>
  </body>
</html>
