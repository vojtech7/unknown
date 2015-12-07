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
      if(isset($_GET['rc_hud'])) {
        $rc_hud = $_GET['rc_hud'];
      }
      else {
        echo "Nebyl zadan hudebnik, ke kteremu se maji priradit skladby.";
        exit();
      }

            //upravuje se existující hudebník
      $moje_vypujcky = array();
      if (isset($_GET["edit"]) and $_GET["edit"] == "true") {
        $sql = "SELECT vyrobni_cislo
                FROM Nastroj
                WHERE rodne_cislo = '{$_GET["rc_hud"]}'";
        //echo $sql;
        $nastroje_radek = user_db_query($sql);
        if ($nastroje_radek == false) {
          echo mysql_error();
        }

        while($nastroj = mysql_fetch_array($nastroje_radek)) {
          array_push($moje_vypujcky, $nastroj['vyrobni_cislo']);
        }
      }
       
       //vypujcene nastroje nekym jinym
      $sql_vypujcene = "SELECT vyrobni_cislo
                        FROM Nastroj
                        WHERE rodne_cislo IS NOT NULL AND rodne_cislo != '$rc_hud'";
      $vypujcene_radek = user_db_query($sql_vypujcene);
      if ($vypujcene_radek == false) {
        echo mysql_error();
      }

      $vypujcene = array();
      while($vypujceny = mysql_fetch_array($vypujcene_radek)) {
        array_push($vypujcene, $vypujceny['vyrobni_cislo']);
      }
      // print_r($vypujcene);
      // exit();

      $sql_jm_hud = "SELECT jmeno, prijmeni FROM Hudebnik WHERE rodne_cislo='$rc_hud'";
      $hudebnik_vysledek = user_db_query($sql_jm_hud);
      $hudebnik_radek = mysql_fetch_array($hudebnik_vysledek);
      $jmeno = $hudebnik_radek['jmeno'];
      $prijmeni = $hudebnik_radek['prijmeni'];
      echo "<h2>Vyberte nastroje pro hudebnika $jmeno $prijmeni</h2>";

      $nadpisy_sloupcu_nastr = array('Typ', 'Výrobce', 'Výrobní èíslo');
      $nazvy_sloupcu_nastr = array('ttype', 'vyrobce', 'vyrobni_cislo');
      $col_count = count($nazvy_sloupcu_nastr);      

      $sql_nastroje = "SELECT ttype, vyrobce, vyrobni_cislo, rodne_cislo FROM Nastroj ORDER BY ttype";
      $nastroje = user_db_query($sql_nastroje);

      $prvni_typ = user_db_query("SELECT ttype FROM Typ ORDER BY ttype LIMIT 1"); //ziskani abecedne prvniho typu
      $typ_old_row = mysql_fetch_array($prvni_typ);
      $typ_old = $typ_old_row[0];

      $edit="";
      if (isset($_GET["edit"]) and $_GET["edit"] == "true") {$edit = "?edit=true"; }
      echo "<form action='vyber_skladby_hud.php' method='post'>";
      //tabulky serazene podle typu, pro kazdou skupinu typu samostatna tabulka
      echo "<table>";
      echo "<tr><td><input type='radio' name='$typ_old' value='none'></td><td>Zadny z techto</td></tr>";
      while($row_nastroj = mysql_fetch_array($nastroje)) {
        $typ = $row_nastroj['ttype'];
        if($typ_old != $typ) {
          echo "</tr></table>";
          echo "<br>";
          echo "<table><tr>";
          echo "<tr><td><input type='radio' name='$typ' value='none'></td><td>Zadny z techto</td></tr>";
        }
        $checked = "";
        $disabled = "";
        if(in_array($row_nastroj['vyrobni_cislo'], $vypujcene)) $disabled = "disabled";        
        if(in_array($row_nastroj['vyrobni_cislo'], $moje_vypujcky)) $checked = "checked";
        echo "<td><input type='radio' name='$typ' value='{$row_nastroj['vyrobni_cislo']}' $checked $disabled></td>";
        for($i=0; $i < $col_count; $i++) {
          echo "<td>$row_nastroj[$i]</td>";
        }
        echo "</tr>";
        $typ_old = $typ;
      }
      echo "</tr>";
      echo "</table>";
      echo "<input type='hidden' name='rc_hud' value='$rc_hud'>";
      if (isset($_GET["edit"]) and $_GET["edit"] == "true") { $button_label = 'Ulozit zmeny'; }
      else { $button_label = 'Vybrat nastroje'; }
      echo "<input type='submit' name='nastr_odeslat' value='$button_label'>";
      if (isset($_GET["edit"]) and $_GET["edit"] == "true")
        echo "<input type='hidden' name='edit' value='true'>";
      
      echo "</form>";



    ?>
  </body>
</html>
