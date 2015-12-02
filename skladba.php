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
      $nazvy_sloupcu = array('nazev', 'styl', 'jmeno', 'delka', 'poradi');
      $nadpisy_sloupcu = array('Název', 'Styl', 'Autor', 'Délka [min]', 'Pořadí');


      if(isset($_POST['id_skl'])) {
        $id_skl = $_POST['id_skl'];
        // header("Location:?id_skl=$id_skl"); //poslu id koncertu do get parametru
      }
      //id koncertu poslano pres get, z odkazu ve vypisu koncertu
      elseif (isset($_GET['id_skl'])) {
        $id_skl = $_GET['id_skl'];
      }
      else {
        echo "Neni zadana konkretni skladba.";
        exit();
      }

      //pole typu nastroju
      $nastroje_jm = array();
      $nastroj_jm_vysl = mysql_query("SELECT ttype FROM Typ");
      while($nastroj_jm_radek = mysql_fetch_array($nastroj_jm_vysl)) {
        array_push($nastroje_jm, $nastroj_jm_radek[0]);
      }

      //pridani nastroju skladbe
      if(!empty($_POST)) {
        foreach($nastroje_jm as $typ) {
          $pocet = $_POST[$typ];
          if($pocet > 0) {
            $poc_na_sklade_vysl = mysql_query("SELECT COUNT(*) FROM Nastroj WHERE ttype = '$typ'");
            $poc_na_sklade_radek = mysql_fetch_array($poc_na_sklade_vysl);
            $poc_na_sklade = $poc_na_sklade_radek[0];
            if($pocet > $poc_na_sklade) {
              echo "Nastroju typu $typ je na sklade pouze $poc_na_sklade.";
              echo "<br><a href='vyber_nastroje_skl.php?id_skl=$id_skl'>Zpet na vyber nastroju</a>";
              exit();
            }
            $sql_prid_nas = "INSERT INTO Hraje_v VALUES (\"$typ\", $id_skl, $pocet)";
            // echo $sql_prid_nas."<br>";
            $pri_nas_vysl = mysql_query($sql_prid_nas);
          }
        }
      }

      $sql_skladba = "SELECT nazev, delka, jmeno, styl
                        FROM Skladba NATURAL JOIN Autor
                        WHERE ID_skladby = '$id_skl'";
      $skladba_vysledek = mysql_query($sql_skladba);
      $skladba = mysql_fetch_array($skladba_vysledek);
      $nazev =  $skladba['nazev'];
      $delka =  $skladba['delka'];
      $jmeno = $skladba['jmeno'];
      $styl = $skladba['styl'];

      echo "<h1>Detail skladby $nazev</h1>";

      echo "<br><ul>
                  <li>Název: $nazev</li>
                  <li>Délka: $delka minut</li>
                  <li>Autor: $jmeno</li>
                  <li>Styl: $styl</li>
                </ul> ";

                // tabulka (typu) nastroju
      $sql = "SELECT ttype, pocet
                      FROM Skladba
                      NATURAL JOIN Hraje_v
                      WHERE ID_skladby = $id_skl";

      $title = "Nástroje, které hrají ve skladbě";
      $nadpisy_sloupcu = array('Typ', 'Počet');      
      $nazvy_sloupcu = array('ttype', 'pocet');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);

                // tabulka koncertu
      $sql = "SELECT nazev_koncertu, datum_a_cas, mesto, adresa
                      FROM Skladba NATURAL JOIN Slozen_z NATURAL JOIN Koncert
                      WHERE ID_skladby = $id_skl";

      $title = "Koncerty, v jejichž programu skladba vystupuje";
      $nadpisy_sloupcu = array('Název', 'Datum a čas', 'Město', 'Adresa');      
      $nazvy_sloupcu = array('nazev_koncertu', 'datum_a_cas', 'mesto', 'adresa');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);

                // tabulka hudebniku
      $sql = "SELECT jmeno, prijmeni
                      FROM Skladba NATURAL JOIN Ma_nastudovano NATURAL JOIN Hudebnik
                      WHERE ID_skladby = $id_skl
                      ORDER BY prijmeni ASC";

      $title = "Hudebníci, kteří mají skladbu nastudovánu";
      $nadpisy_sloupcu = array('Jméno', 'Příjmení');      
      $nazvy_sloupcu = array('jmeno', 'prijmeni');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);

      echo "<a href='aranzer.php'>Zpet na vypis skladeb</a>";
    ?>
  </body>
</html>
