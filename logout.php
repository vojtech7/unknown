<?php
  include "connect.php";

  session_start();
  session_destroy();

  echo "<h2>Odhl�en� prob�hlo �sp�n�.</h2>";
  echo "<a href='index.php'>�vodn� str�nka</a><br>";
  // header('Location:index.php');
?>