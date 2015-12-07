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
      include 'functions.php';

      if(isset($_POST['rc_hud'])) {
        $rc_hud = $_POST['rc_hud'];
        // header("Location:?rc_hud=$rc_hud"); //poslu id koncertu do get parametru
      }
      else if(isset($_GET['rc_hud'])) {
        $rc_hud = $_GET['rc_hud'];
      }
      else {
        echo "Nebyl zadan hudebnik, ke kteremu se maji priradit skladby.";
        exit();
      }

      //ziskani typu nastroju z DB
      $typy_vysledek = user_db_query("SELECT ttype FROM Typ ORDER BY ttype");
      $typy = array();
      while($typ_radek = mysql_fetch_array($typy_vysledek)) {
        array_push($typy, $typ_radek[0]);
      }
      
      //upravuje se stavajici hrac, ID jsou uz v db
      if (isset($_GET["edit"]) and $_GET["edit"] == "true") {
        $sql_id = "SELECT vyrobni_cislo
                   FROM Nastroj
                   Where rodne_cislo = '$rc_hud'";
        $result_id = user_db_query($sql_id);
        $nastroje_id = array();
        while ($row = mysql_fetch_row($result_id)) {
          array_push($nastroje_id, $row[0]);
        }
      
        
      }
      else {
        //ziskani IDcek nastroju z radio buttonu predchoziho formulare
        $nastroje_id = array();
        foreach($typy as $typ) {
          if(isset($_POST[$typ]) and $_POST[$typ] != 'none') {
            array_push($nastroje_id, $_POST[$typ]);
          }
        }
      } 
      //ziskani jmen(typu) z IDcek nastroju
      $nastroje_jm = array();
      foreach($nastroje_id as $n_id) {
        $nastroj_jm_vysl = user_db_query("SELECT ttype FROM Nastroj WHERE vyrobni_cislo = $n_id");
        $nastroj_jm_radek = mysql_fetch_array($nastroj_jm_vysl);
        array_push($nastroje_jm, $nastroj_jm_radek[0]);
      }

      //ziskani ID nacvicenych skladeb (pouze uprava stavajiciho)
      if (isset($_GET["edit"]) and $_GET["edit"] == "true") {
        $sql_nastud = "SELECT ID_skladby
                       FROM Hudebnik NATURAL JOIN Ma_nastudovano
                       WHERE rodne_cislo = '$rc_hud';";
        $vysledek = user_db_query($sql_nastud);
        $nastud = array();
        while ($row = mysql_fetch_array($vysledek)) {
          array_push($nastud, $row[0]);
        }
      }
      
      // uprava nastroju hudebnika (nejdrive se smazou ty stare, pote se nahrajou ty nove)
      $sql_dlt = "UPDATE Nastroj
                  SET rodne_cislo = NULL
                  WHERE rodne_cislo = '$rc_hud'";
      if (user_db_query($sql_dlt) == false) {
        echo $sql_dlt;
      }
  
      if (isset($_POST["edit"])) {
        header("Location:hud_detail.php?rc_hud=$rc_hud");
      }
      //prirazeni nastroju hudebnikovi
      if(!empty($nastroje_id)) {
        foreach($nastroje_id as $n) {
          $sql_prid_nastr = "UPDATE Nastroj SET rodne_cislo = '$rc_hud' WHERE vyrobni_cislo = '$n'";
          $prid_skl_vysl = user_db_query($sql_prid_nastr);
        }
      }
      elseif (isset($_GET["edit"] )) {
        
      }
      else {
        echo "Je treba zadat aspon jeden nastroj pro hudebnika.";
        echo "<a href='vyber_nastroje_hud.php?rc_hud=$rc_hud'>Zpet na vyber nastroju</a>";
        exit();
      }

      $nazvy_sloupcu_skl = array('nazev', 'jmeno', 'styl');
      $col_count_skl = count($nazvy_sloupcu_skl);

      $sql_jm_hud = "SELECT jmeno, prijmeni FROM Hudebnik WHERE rodne_cislo='$rc_hud'";
      $hudebnik_vysledek = user_db_query($sql_jm_hud);
      $hudebnik_radek = mysql_fetch_array($hudebnik_vysledek);
      $jmeno = $hudebnik_radek['jmeno'];
      $prijmeni = $hudebnik_radek['prijmeni'];
      echo "<h2>Vyberte skladby pro hudebnika $jmeno $prijmeni</h2>";

      //jen ty skladby, v nichz vystupuje nastroj, na ktery hudebnik hraje
      $sql_skl = "SELECT DISTINCT ID_skladby, nazev, jmeno, styl
                           FROM Skladba NATURAL JOIN Hraje_v NATURAL JOIN Autor
                           WHERE ttype = '$nastroje_jm[0]'";
      for ($i=1; $i < count($nastroje_jm); $i++) { 
        $sql_skl .= " OR ttype = '$nastroje_jm[$i]'";
      }
      $sql_skl .= " ORDER BY jmeno";
      // echo $sql_skl;
      $skladby = user_db_query($sql_skl);

      echo "<form action='hud_detail.php' method='post'>";
      echo "<table>";
      while($row_skl = mysql_fetch_array($skladby)){
        echo "<tr>";
        $checked = "";
        if(in_array($row_skl[0], $nastud)) $checked = "checked";        
        echo "<td><input type='checkbox' name='skladby[]' value='$row_skl[0]' $checked></td>";
        for ($i=1; $i <= $col_count_skl; $i++) {
          echo "<td>{$row_skl[$i]}</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
      echo "<br>";

      echo "<input type='hidden' name='rc_hud' value='$rc_hud'>";
      echo "<input type='submit' name='skl_odeslat' value='Vybrat skladby'>";
      echo "</form>";

    ?>
  </body>
</html>
