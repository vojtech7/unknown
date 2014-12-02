<?php

if(!empty($_POST)) {
  $jmeno = $_POST["name"];
  $vek = $_POST["age"];
  $email = $_POST["email"];
  // if(empty($_POST["n_jm_lyz"]) || empty($_POST["n_cn_lyz"])) echo "Nezadali jste všechny nové údaje.";
  // else { 
  //   pg_exec($connect,"update lyze set zn_mod='$n_jm_lyz' where id_l=$id_lyz");
  //   pg_exec($connect,"update lyze set cena='$n_cn_lyz' where id_l=$id_lyz");
  // }
  echo "jmeno: " . $jmeno . "<br>";
  echo "vek: " . $vek . "<br>";
  echo "email: " . $email . "<br>";
}

?>