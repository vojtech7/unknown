<?php
  include 'connect.php';
  include 'functions.php';
  $id_kon = 13;
  $id_skladeb = array(3, 4, 6);
  //hudebnici majici nacvicenu alespon jednu skladbu koncertu serazeni dle poctu nacvicenych skladeb
  $sql_kandidati = "SELECT rodne_cislo AS rc, jmeno, prijmeni,
                    (SELECT COUNT(*) FROM Nastroj NATURAL JOIN Hudebnik WHERE rodne_cislo = rc) AS nastroju
                    FROM Hudebnik NATURAL JOIN Ma_nastudovano NATURAL JOIN Skladba NATURAL JOIN Slozen_z NATURAL JOIN Koncert
                    WHERE ID_koncertu = $id_kon
                    ORDER BY nastroju";
  $kandidati_vysl = user_db_query($sql_kandidati);
  // $kandidati_vysl = mysql_query($sql_kandidati);
  $rc_kandidatu = array();
  while($kandidat_radek = mysql_fetch_array($kandidati_vysl)) {
    array_push($rc_kandidatu, $kandidat_radek['rc']);
  }
  print_r($rc_kandidatu);

  foreach($id_skladeb as $ids) {  //pro kazdou skladbu v koncertu
    $sql_nastroje_skladby = "SELECT ttype, pocet FROM Skladba NATURAL JOIN Hraje_v WHERE ID_skladby = $ids";
    $nastroje_vysl = user_db_query($sql_nastroje_skladby);
    $nastroje = array();
    while($nastroj_radek = mysql_fetch_array($nastroje_vysl)) {
      if($nastroj_radek['pocet'] > 0)
        $nastroje[$nastroj_radek['ttype']] = $nastroj_radek['pocet'];  //typ => pocet
    }

    foreach ($nastroje as $typ => $pocet) {   //pro kazdy nastroj ve skladbe
      $zvoleni_pro_skladbu = array();
      for ($i=0; $i < $pocet; $i++) {   //nastroj muze byt zastoupen ve skladbe vicekrat
        
        //projedeme vsechny kandidaty
        foreach ($rc_kandidatu as $rck) {
          //ma kandidat nastudovanu skladbu?
          $sql_ma_nastud = "SELECT EXISTS(SELECT ID_skladby, rodne_cislo
                                          FROM Ma_nastudovano
                                          WHERE ID_skladby = $ids AND rodne_cislo = '$rck')";
          $ma_nastudovano_radek = mysql_fetch_array(mysql_query($sql_ma_nastud));
          $ma_nastudovano = $ma_nastudovano_radek[0];
          if(!$ma_nastudovano) continue;  //pokud skladbu neumi, jdeme na dalsiho

          //hraje kandidat na dany typ nastroje?
          $sql_hraje_na = "SELECT EXISTS(SELECT vyrobni_cislo
                                         FROM Hudebnik NATURAL JOIN Nastroj
                                         WHERE rodne_cislo = '$rck' AND ttype = '$typ')";
          $hraje_na_radek = mysql_fetch_array(mysql_query($sql_hraje_na));
          $hraje_na = $hraje_na_radek[0];
          if(!$hraje_na) continue;  //pokud na nastroj nehraje, jdeme na dalsiho
          echo "$rck $hraje_na $typ <br>";
          //TODO: odeber z kandidatu a presun do zvolenych pro skladbu
        }

      }
    }   //nastroj ve skladbe
  }  //skladba v koncertu
  
?>