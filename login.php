<?php
  include "connect.php";

  if(!empty($_POST)) {
    $login = $_POST['login'];
    $heslo = $_POST['heslo'];
    $page = $_GET['page'];    //napr. "manazer.php"

    if($page == "admin.php") {  //admin
      $hash_zadane_admin = sha1($heslo);
      if($login == "cimrman" and $hash_zadane_admin == 'c856676e7c7aa3b1217c8c809b6e5c9cf77427a6') {
        session_start();
        $_SESSION["id"] = $page;
        $_SESSION["time"] = time();
        echo "Autentizace prob�hla �sp�n�.";
        header("Location:$page");
        echo "<a href='{$page}'>Pokra�ovat >></a><br>";
      }
      else {
        echo "Nespr�vn� administr�torsk� heslo.<br>";
      }
    }
    else {  //neadmin
      $sql = 'select * from Uzivatel where login="'.$login.'"';
      $vysledek = mysql_query($sql);

      //dotaz nic nevratil
      if ($vysledek == false or mysql_num_rows($vysledek) == 0) {
        echo "U�ivatel $login neni zaznamen�n v datab�zi.<br>";
      }
      //dotaz vratil radek
      else {
        $radek = mysql_fetch_array($vysledek);    //dotaz vrati jen jeden radek
        // var_dump($radek);
        $hash_zadane = sha1($heslo);
        $hash_prave = $radek['heslo_hash'];
        $role = $radek['role'];
        $role = $role . ".php";

        echo "<br>role: $role, page: $page<br>";
        if($role == $page) {  //jestli je spravna role
          print_r($hash_prave);
          echo "<br>";
          print_r($hash_zadane);
          if($hash_prave == $hash_zadane) {   //spravne heslo
            session_start();
            $_SESSION["id"] = $page;
            $_SESSION["time"] = time();
            echo "Autentizace prob�hla �sp�n�.";
            header("Location:$page");
            echo "<a href='?page={$page}'>Pokra�ovat >></a>";
          }
          else {
            echo "Nespr�vn� heslo.";
          }
          
        }
        else {  //neni spravna role
          echo "Jin� typ u�ivatele.";
        }

      }//dotaz vratil radek
      
    }//neadmin

  }//POST not empty

?>
