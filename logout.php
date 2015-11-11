<?php
  include "connect.php";

  session_start();
  session_destroy();

  echo "<h2>Odhlá¹ení probìhlo úspì¹nì.</h2>";
  // header('Location:index.php');
?>