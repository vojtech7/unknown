<?php
  include "connect.php";

  session_start();
  unset($_SESSION['id']);

  echo "Odhl�en� prob�hlo �sp�n�.";
  header('Location:index.php');
?>