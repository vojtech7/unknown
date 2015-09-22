<?php

  //pridani radku do tabulky
  if(!empty($_POST)) {
    // if($_GET['user'] == "personalista") {  //pk je string(presneji CHAR(11))
      $jmeno = $_POST["jmeno"];
      $prijmeni = $_POST["prijmeni"];
      $rodne_cislo = $_POST["rodne_cislo"];
      // echo "jmeno: " . $jmeno . "<br>";
      // echo "prijmeni: " . $prijmeni . "<br>";
      // echo "rodne_cislo: " . $rodne_cislo . "<br>";
      $insert_row = "INSERT INTO ".$tabulka." VALUES (".$rodne_cislo.", ".$jmeno.", ".$prijmeni.");";
      echo "insert_row: ".$insert_row."<br>";
      // $insert_success = mysql_query($insert_row);
    // }

  }

?>