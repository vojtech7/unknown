<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <script type="text/javascript" src="js/netteForms.js"></script>
    <script src="js/libs/jquery-2.1.1.js"></script>
    <style> .required label { color: maroon } </style>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <title>Aran¾ér Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    include "connect.php";
    include 'functions.php';
    use Nette\Forms\Form;

    session_start();
    $role = 'aranzer';
    $nadpisy_sloupcu = array('Název', 'Délka [min]', 'Jméno autora');
    $nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'jmeno');
    $tabulka_upravy = "Skladba";
    //uzivatel neni prihlasen
    // if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
    if(0){
      echo "
      <form action='login.php?page=$role.php' method='post' enctype='multipart/form-data'>
        <h3>Pøihlá¹ení</h3>
        Login:<input type='text' name='login'><br>
        Heslo:<input type='password' name='heslo'>
        <input type='submit' value='Pøihlásit'>         
      </form>";
    }

    //timeout
    // elseif(time() - $_SESSION['timestamp'] > 900) {
    //   session_destroy();
    //   header("Location:timeout.php");
    // }

    else {
     //ziskani jmen autoru pro dalsi praci
    $sql = "SELECT jmeno FROM Autor";
    $autori = mysql_query($sql);

    for ($i=0; $i < mysql_num_rows($autori); $i++) { 
      $row = mysql_fetch_array($autori, MYSQL_ASSOC);
      $seznam_jmen[$row["jmeno"]] = $row["jmeno"];
    }
    $page = "aranzer.php";
    echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
    echo '<div id="menu"><ul>';
     // echo "<ul><li><a href='P_add_form_show()'>Pøidat zamìstnance</a></li>";
    echo "<button class=\"skladba\" onclick='P_add_form_show(\"$role\", \"skladba\")'>Pøidat skladbu</button><br>";
    echo "<button class=\"autor\" onclick='P_add_form_show(\"$role\", \"autor\")'>Pøidat autora</button><br>";
    echo "<button onclick='switch_table(\"skladba\")'>Zobrazit skladby</button><br>";
    echo "<button onclick='switch_table(\"autor\")'>Zobrazit autory</button><br>";
    echo "</ul><div>";

    //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern" class="skladba">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání skladby</span>
            <tr>';
        foreach ($nadpisy_sloupcu as $value) {
          echo "<td>". $value ."</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($nazvy_sloupcu as $value) {
          if ($value === "ID_skladby") continue; 
          if ($value !== "jmeno") {
             echo "<td> <input type=\"text\" class=\"form-control filter_". $value ."\"></td>";
            }
          else
            { echo "<td><select class=\"filter_jmeno form-control\">";
              echo "<option value=\"\" selected=\"selected\"></option>";
              foreach ($seznam_jmen as $key) {
                echo "<option value=\"".$key."\">".$key."</option>";
              }
              echo "</td>";
            }
            
        }

        echo "</tr>";
        echo "</table>";


        //pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkou
        //odstraneni skladby z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM $tabulka_upravy 
                         WHERE $pk ='".$_GET["delete"]."';";
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
          header("Location:aranzer.php");
        }
        //pridani nebo uprava radku tabulky
        if(isset($_GET["nazev"]) and isset($_GET["delka"]) and isset($_GET["jmeno"]) and isset($_GET["edit"])) {
          $nazev = $_GET["nazev"];
          $delka = $_GET["delka"];
        if ($_GET["edit"]=="edit") {
              //upravuje se radek
              $ID_skladby = $_GET["id"];
              $sql = "SELECT ID_autora 
                      FROM Autor 
                      WHERE jmeno= '".$_GET["jmeno"]."'";
              $cislo = mysql_fetch_row(mysql_query($sql));
              $ID_autora=$cislo[0];
              $sql = "UPDATE $tabulka_upravy 
                      SET ID_skladby = $ID_skladby, nazev ='$nazev', delka=$delka, ID_autora =$ID_autora 
                      WHERE ID_skladby =$ID_skladby";
              mysql_query($sql);
            }
            elseif ($_GET["edit"]=="add") {
              $sql = "SELECT max(ID_skladby) FROM  $tabulka_upravy.";
              $cislo = mysql_fetch_row(mysql_query($sql));
              $ID_skladby = 1 + $cislo[0];

              $sql = "SELECT ID_autora FROM Autor WHERE jmeno= \"".$_GET["jmeno"]."\"";
              $cislo = mysql_fetch_row(mysql_query($sql));
              $ID_autora=$cislo[0];
              

              $insert_row = "INSERT INTO $tabulka_upravy 
                             VALUES ('$ID_skladby', '$nazev', '$delka', '$ID_autora');";
              $insert_success = mysql_query($insert_row);
              if(!$insert_success) echo "nepodarilo se vlozit polozku";
            }
          
          header("Location:aranzer.php");
        }
        /*tahani dat z databaze*/
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

      $tabulka_vypis = "Autor natural join Skladba ";
      $sql = "SELECT ID_skladby, nazev, delka, jmeno FROM   ".$tabulka_vypis;
      $title = "Seznam skladeb";
      $nadpisy_sloupcu = array('Název', 'Délka [min]', 'Jméno autora');
      $nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'jmeno');
      $ignore = array('ID_skladby');
      $butons = array("edit", "delete");
      $PK = "ID_skladby";
      echo "<div class=\"skladba\">";
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu, $ignore, $butons, $PK, $role, $page);
      echo "</div>";
      

      $tabulka_vypis = "Autor";
      $sql = "SELECT * FROM   ".$tabulka_vypis;
      $title = "Seznam skladeb";
      $nadpisy_sloupcu = array('Jméno', 'Začátek tvorby', 'Konec tvorby', 'Styl');
      $nazvy_sloupcu = array('ID_autora', 'jmeno', 'zacatek_tvorby', 'konec_tvorby', 'styl');
      $ignore = array('ID_autora');
      $butons = array("edit", "delete");
      $PK = "ID_autora";
      echo "<div class=\"autor\">";
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu, $ignore, $butons, $PK, $role, $page);
      echo "</div ";



      echo '</div>
            <!-- formular pro pridani -->
            <div id="P_add_form" class="abc">
            <!-- Popup Div Starts Here -->
            <div id="popupContact">
            <!-- Contact Us Form -->
            <img id="close" src="img/close-icon.png" onclick ="P_add_form_hide()">



            <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';
  require 'Nette/loader.php';

    require_once 'Nette/Forms/Form.php';

    $add_skladba = new Form;
    $add_skladba->setAction('aranzer.php');
    $add_skladba->setMethod('GET');


    $add_skladba->addSelect('jmeno', 'Jmeno autora', $seznam_jmen)
      ->setPrompt( 'Zadejte jm�no autora');
    $add_skladba->addText('nazev', 'Nazev:')
      ->addRule(Form::FILLED, 'Zadejte nazev skladby');
    $add_skladba->addText('delka', 'D�lka [min]')
      ->addRule(Form::FILLED, 'Zadejte delku skladby');
    $add_skladba->addHidden('edit');
    $add_skladba->addHidden('id');
    $add_skladba->addSubmit('send', 'Pridat');


  echo $add_skladba; // vykresli formular

  $sub1 = $add_skladba->addContainer('first');

  if ($add_skladba->isSuccess()) {
    echo 'Formuláø byl správnì vyplnìn a odeslán';
      $values = $add_skladba->getValues();
    dump($values);
  }

  /**************************************
      Dalsi formular
  ***************************************/
    $add_autor = new Form;
    $add_autor->setAction('aranzer.php');
    $add_autor->setMethod('GET');


    $add_autor->addSelect('jmeno', 'Jmeno autora', $seznam_jmen)
      ->setPrompt( 'Zadejte jm�no autora');
    $add_autor->addText('nazev', 'Nazev:')
      ->addRule(Form::FILLED, 'Zadejte nazev skladby');
    $add_autor->addText('delka', 'Délka [min]')
      ->addRule(Form::FILLED, 'Zadejte delku skladby');
    $add_autor->addHidden('edit');
    $add_autor->addHidden('id');
    $add_autor->addSubmit('send', 'Pridat');


  echo $add_autor; // vykresli formular

  $sub1 = $add_autor->addContainer('first');

  if ($add_autor->isSuccess()) {
    echo 'Formuláø byl správnì vyplnìn a odeslán';
      $values = $add_autor->getValues();
    dump($values);
  }
  //vypisuje html kod na dalsich radcich
  echo '
  </div>
  <!-- Popup Div Ends Here -->
  </div>
  <!-- Display Popup Button -->
  <!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->';
  }//uzivatel je prihlasen
  ?>
  <script type="text/javascript">
    $('.autor').hide();
  </script>    
  </body>
</html>
