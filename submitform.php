<?php

if(!empty($_POST)) {
  $jmeno = $_POST["jmeno"];
  $prijmeni = $_POST["prijmeni"];
  $rodne_cislo = $_POST["rodne_cislo"];
  // if(empty($_POST["n_jm_lyz"]) || empty($_POST["n_cn_lyz"])) echo "Nezadali jste všechny nové údaje.";
  // else { 
  //   pg_exec($connect,"update lyze set zn_mod='$n_jm_lyz' where id_l=$id_lyz");
  //   pg_exec($connect,"update lyze set cena='$n_cn_lyz' where id_l=$id_lyz");
  // }
  echo "jmeno: " . $jmeno . "<br>";
  echo "prijmeni: " . $prijmeni . "<br>";
  echo "rodne_cislo: " . $rodne_cislo . "<br>";
}

?>