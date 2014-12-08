<?php
  include "connect.php";

  session_start();
  unset($_SESSION['id']);

  echo "Odhlá¹ení probìhlo úspì¹nì.";
  header('Location:index.php');
?>