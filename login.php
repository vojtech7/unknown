<?php
  include "connect.php";
  session_save_path("./tmp");

  if(!empty($_POST)) {
    if(isset($_POST['login'])) $login = $_POST['login'];
    else if(isset($_POST['rodne_cislo'])) $login = $_POST['rodne_cislo'];
    $heslo = $_POST['heslo'];
    $page = $_GET['page'];    //napr. "manazer.php"

    //admin
    if($page == "admin.php") {  
      if($login == "cimrman" and sha1($heslo) == 'c856676e7c7aa3b1217c8c809b6e5c9cf77427a6') {
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['timestamp'] = time();
        $_SESSION['role'] = "admin";
        echo "Autentizace probìhla úspì¹nì.";
        header("Location:$page");
        echo "<a href='{$page}'>Pokraèovat >></a><br>";
      }
      else {
        echo "Nesprávné administrátorské heslo.<br>";
      }
    }
    //hudebnik
    elseif($page == "hudebnik.php") {   
      $sql = "SELECT * FROM Hudebnik WHERE rodne_cislo='$login'";
      $vysledek = mysql_query($sql);
      //dotaz nic nevratil
      if ($vysledek == false or mysql_num_rows($vysledek) == 0) {
        echo "Hudebník s rodným číslem $login není zaznamenán v databázi.<br>";
      }
      //dotaz vratil radek
      else {
        $row = mysql_fetch_array($vysledek);    //dotaz vrati jen jeden radek
        // var_dump($row);
        $hash_zadane = sha1($heslo);
        $hash_prave = $row['heslo_hash'];
        $login = $row['rodne_cislo'];
        if($hash_prave == $hash_zadane) {   //spravne heslo
          session_start();
          $_SESSION['logged_in'] = true;
          $_SESSION['timestamp'] = time();
          $_SESSION['role'] = 'hudebnik';
          $_SESSION['user_login'] = $login;
          echo "Autentizace probìhla úspì¹nì.";
          print_r($_SESSION);
          header("Location:$page");
          echo "<a href='?page={$page}'>Pokraèovat >></a>";
        }
        else {
          echo "Nesprávné heslo.";
        }
      }
    }
    //neadmin, nehudebnik
    else {  
      $sql = "SELECT * FROM Uzivatel WHERE login='$login'";
      $vysledek = mysql_query($sql);
      //dotaz nic nevratil
      if ($vysledek == false or mysql_num_rows($vysledek) == 0) {
        echo "U¾ivatel $login není zaznamenán v databázi.<br>";
      }
      //dotaz vratil radek
      else {
        $row = mysql_fetch_array($vysledek);    //dotaz vrati jen jeden radek
        // var_dump($row);
        $hash_zadane = sha1($heslo);
        $hash_prave = $row['heslo_hash'];
        $role = $row['role'];
        $login = $row['login'];

        // echo "<br>role: $role, page: $page<br>";
        if(($role.'.php') == $page) {  //jestli je spravna role
          // print_r($hash_prave);
          // echo "<br>";
          // print_r($hash_zadane);
          if($hash_prave == $hash_zadane) {   //spravne heslo
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['timestamp'] = time();
            $_SESSION['role'] = $role;
            $_SESSION['user_login'] = $login;
            echo "Autentizace probìhla úspì¹nì.";
            print_r($_SESSION);
            header("Location:$page");
            echo "<a href='?page={$page}'>Pokraèovat >></a>";
          }
          else {
            echo "Nesprávné heslo.";
          }
          
        }
        else {  //neni spravna role
          echo "Jiný typ u¾ivatele.";
        }

      }//dotaz vratil radek
      
    }//neadmin

  }//POST not empty

?>
