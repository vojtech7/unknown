<?php
  include "connect.php";

  session_start();
  // unset($_SESSION['id']);
  session_destroy();

  echo "<h2>Odhlá¹ení probìhlo úspì¹nì.</h2>";
  // header('Location:index.php');
?>