<?php
  include "connect.php";

  session_start();
  // unset($_SESSION['id']);
  session_destroy();

  echo "<h2>Odhl�en� prob�hlo �sp�n�.</h2>";
  // header('Location:index.php');
?>