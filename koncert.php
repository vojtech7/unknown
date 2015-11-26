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
        //pridani skladeb koncertu
        foreach ($skladby as $i => $s) {
          $p = $i + 1;
          $sql_prid_skl = "INSERT INTO Slozen_z VALUES ('$id_kon', '$s', '$p');";
          $prid_skl_vysl = mysql_query($sql_prid_skl);
          echo $sql_prid_skl;
          echo "<br>";
        }
        print_r($skladby);
        echo "<br>";
      }
      // else {
      //   echo "Nejsou zadany skladby pro koncert.";
      //   exit();
      // }

      $sql_naz_kon = "SELECT * FROM Koncert WHERE ID_koncertu='$id_kon'";
      $sql_delka_kon = "SELECT SUM(delka) FROM Slozen_z NATURAL JOIN
       						(SELECT delka, ID_skladby
                        	 FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE ID_koncertu = $id_kon";
      $koncert_vysledek = mysql_query($sql_naz_kon);
      $koncert = mysql_fetch_array($koncert_vysledek);
      $nazev =  $koncert['nazev_koncertu'];
      $datum =  $koncert['datum_a_cas'];
      $mesto =  $koncert['mesto'];
      $adresa = $koncert['adresa'];
      $delka = mysql_fetch_array(mysql_query("$sql_delka_kon"));
      // print_r($koncert);  //FIXME: zobrazit privetiveji, nez takto

      echo "<h1>Detail koncertu $nazev</h1>";

      echo "<br><ul>
                  <li>Datum: $datum</li>
                  <li>Město: $mesto</li>
                  <li>Adresa: $adresa</li>
                  <li>Délka: ".$delka['SUM(delka)']." minut</li>
                </ul> ";
      
                //tabulka skladeb
      $sql = "SELECT *
                      FROM Slozen_z
                      NATURAL JOIN (
                        SELECT jmeno, nazev, styl, delka, ID_skladby
                        FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE ID_koncertu =$id_kon
                      ORDER BY poradi ASC";
      
      $title = "Seznam skladeb";
  
      $nadpisy_sloupcu = array('Pořadí', 'Jméno', 'Název', 'Styl', 'Délka');
      $nazvy_sloupcu = array('poradi', 'jmeno', 'nazev', 'styl', 'delka');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);

                //tabulka hudebniku
      $sql = "SELECT jmeno, prijmeni, ID_koncertu
                      FROM Hudebnik
                      NATURAL JOIN Vystupuje_na
                      WHERE ID_koncertu = $id_kon
                      ORDER BY prijmeni ASC";

      $title = "Seznam hudebníků";
      $nadpisy_sloupcu = array('Jméno', 'Příjmení');      
      $nazvy_sloupcu = array('jmeno', 'prijmeni');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);
      
                //tabulka autoru
      $sql = "SELECT jmeno
                      FROM Slozen_z
                      NATURAL JOIN (
                        SELECT jmeno, styl, nazev, delka, ID_skladby
                        FROM Skladba NATURAL JOIN Autor) AS alias
                      WHERE ID_koncertu =$id_kon
                      ORDER BY poradi ASC";

      $nadpisy_sloupcu = array('Jméno');
      $nazvy_sloupcu = array('jmeno');      
      $title = "Seznam autorů";
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);
      
              //tabulka nastroju
      $sql = "SELECT ttype, MAX(pocet)
				FROM Slozen_z
				NATURAL JOIN (
					SELECT ttype, pocet, ID_skladby
			  		FROM Skladba NATURAL JOIN Hraje_v) AS alias
				WHERE ID_koncertu = 13
				GROUP BY ttype";

	  $nadpisy_sloupcu = array('Typ', 'Počet');
      $nazvy_sloupcu = array('ttype', 'MAX(pocet)');      
      $title = "Seznam nástrojů";
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);
    ?>
  </body>
</html>
