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

    session_save_path("./tmp");
    session_start();
    $role = 'aranzer';
    $skladba = array();
    $skladba["nadpisy_sloupcu"] = array('Název', 'Délka [min]', 'Jméno autora');
    $skladba["nazvy_sloupcu"] = array('ID_skladby', 'nazev', 'delka', 'jmeno');
    $skladba["tabulka_uprav"] = "Skladba";
    $skladba["ignore"] = array('ID_skladby');
    $skladba["sql"] = "SELECT ID_skladby, nazev, delka, jmeno 
            FROM   Autor natural join Skladba";
    $skladba["title"] = "Seznam skladeb";
    $skladba["buttons"] = array("edit", "delete");
    $skladba["PK"] = "ID_skladby";

    $autor = array();
    $autor["nadpisy_sloupcu"] = array('Jméno', 'Začátek tvorby', 'Konec tvorby', 'Styl');
    $autor["nazvy_sloupcu"] = array('ID_autora', 'jmeno', 'zacatek_tvorby', 'konec_tvorby', 'styl');
    $autor["tabulka_uprav"] = "Autor";
    $autor["ignore"] = array('ID_autora');
    $autor["sql"] = "SELECT * 
            FROM   Autor";
    $autor["title"] = "Seznam autoru";
    $autor["buttons"] = array("edit", "delete");
    $autor["PK"] = "ID_autora";
    
    //uzivatel neni prihlasen
    if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
    // if(0){
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
      $_SESSION['timestamp'] = time();
       //ziskani jmen autoru pro dalsi praci
      $sql = "SELECT jmeno FROM Autor";
      $autori = user_db_query($sql);

      for ($i=0; $i < mysql_num_rows($autori); $i++) { 
        $row = mysql_fetch_array($autori, MYSQL_ASSOC);
        $seznam_jmen[$row["jmeno"]] = $row["jmeno"];
    }
    $page = "aranzer.php";
    echo "<div id=logout_btn>Příhlášen $role {$_SESSION['user_login']}<br><a href='logout.php'>Odhlásit se</a></div>";
    echo '<div id="menu"><ul>';
     // echo "<ul><li><a href='P_add_form_show()'>Pøidat zamìstnance</a></li>";
    echo "<li><button class=\"skladba\" onclick='P_add_form_show(\"$role\", \"Seznam skladeb\")'>Pøidat skladbu</button><br></li>";
    echo "<li><button class=\"autor\" onclick='P_add_form_show(\"$role\", \"Seznam autoru\")'>Pøidat autora</button><br></li>";
    echo "<li><button onclick='switch_table(\"skladba\")'>Zobrazit skladby</button><br></li>";
    echo "<li><button onclick='switch_table(\"autor\")'>Zobrazit autory</button><br></li>";
    echo "</ul><div>";

    //tabulky se vstupy pro hledani
    
      echo "<div class=\"skladba\">";
       print_search_table($skladba["nadpisy_sloupcu"], $skladba["nazvy_sloupcu"], "skladba", $seznam_jmen, $skladba["ignore"]);
      echo "</div>";
      echo "<div class=\"autor\">"; 
       print_search_table($autor["nadpisy_sloupcu"], $autor["nazvy_sloupcu"], "autor", $seznam_jmen, $autor["ignore"]);
      echo "</div>";

        //pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkami
        //odstraneni skladby z tabulky
        //odstraňování je pro obě tabulky společné
        if(isset($_GET['delete'])) {
          if ($_GET["tabulka"]=="Autor")
            $pk = $autor["PK"];
          else
            $pk = $skladba["PK"];

          $delete_row = "DELETE FROM {$_GET["tabulka"]} 
                         WHERE $pk ='{$_GET["delete"]}';";
          $delete_success = user_db_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
          header("Location:aranzer.php");
        }

        /*************************************
            PŘIDÁVÁNÍ A ÚPRAVA SKLADEB
        *************************************/

        //pridani nebo uprava radku tabulky
        if(isset($_GET["nazev"]) and isset($_GET["delka"]) and isset($_GET["jmeno"]) and isset($_GET["edit_skladba"])) {
          $nazev = $_GET["nazev"];
          $delka = $_GET["delka"];
        if ($_GET["edit_skladba"]=="edit") {
              echo "upravuje se radek<br>";
              //upravuje se radek
              $ID_skladby = $_GET["id"];
              $sql_id = "SELECT ID_autora 
                         FROM Autor 
                         WHERE jmeno= '{$_GET["jmeno"]}'";
              $cislo = mysql_fetch_row(user_db_query($sql_id));
              $ID_autora=$cislo[0];
              $sql = "UPDATE {$skladba['tabulka_uprav']} 
                      SET ID_skladby = $ID_skladby, nazev ='$nazev', delka=$delka, ID_autora =$ID_autora 
                      WHERE ID_skladby =$ID_skladby";
              //echo $sql;
              user_db_query($sql);
              header("Location:aranzer.php");
            }
        elseif ($_GET["edit_skladba"]=="add") {
            $sql = "SELECT max(ID_skladby) FROM  {$skladba['tabulka_uprav']} ;";
            $cislo = mysql_fetch_row(user_db_query($sql));
            $ID_skladby = 1 + $cislo[0];

            $sql = "SELECT ID_autora FROM Autor WHERE jmeno= \"".$_GET["jmeno"]."\"";
            $cislo = mysql_fetch_row(user_db_query($sql));
            $ID_autora=$cislo[0];
            

            $insert_row = "INSERT INTO {$skladba['tabulka_uprav']} 
                           VALUES ('$ID_skladby', '$nazev', '$delka', '$ID_autora');";
           // echo $insert_row;
            $insert_success = user_db_query($insert_row);
            if(!$insert_success) echo "nepodarilo se vlozit polozku";
            header("Location:vyber_nastroje_skl.php?id_skl=$ID_skladby");
          }
          if ($_GET["edit_skladba"]=="add")
            header("Location: vyber_nastroje_skl.php?id_skl=$ID_skladby");
          else
          header("Location:aranzer.php");
        }
        /************************************************
              PŘIDÁVÁNÍ A ÚPRAVA AUTORŮ
        ************************************************/

        //pridani nebo uprava radku tabulky
        if(isset($_GET["jmeno_autora"]) and isset($_GET["zacatek_tvorby"]) and isset($_GET["konec_tvorby"]) and isset($_GET["styl"]) and isset($_GET["edit_autor"])) {
        $jmeno = $_GET["jmeno_autora"];
        $zacatek_tvorby = $_GET["zacatek_tvorby"];
        $konec_tvorby = $_GET["konec_tvorby"];
        $styl = $_GET["styl"];
        if ($_GET["edit_autor"]=="edit") {
              //upravuje se radek
              $ID_autora= $_GET["ID_autora"];
              $sql = "UPDATE {$autor['tabulka_uprav']} 
                      SET ID_autora = $ID_autora, jmeno ='$jmeno', zacatek_tvorby = $zacatek_tvorby, konec_tvorby = $konec_tvorby, styl = $styl
                      WHERE ID_autora =$ID_autora";
              user_db_query($sql);

            }
        elseif ($_GET["edit_autor"]=="add") {
          $sql = "SELECT max(ID_autora) FROM  {$autor['tabulka_uprav']} ;";
          $cislo = mysql_fetch_row(user_db_query($sql));
          $ID_autora = 1 + $cislo[0];

          $insert_row = "INSERT INTO {$autor['tabulka_uprav']} 
                         VALUES ('$ID_autora', '$jmeno', '$zacatek_tvorby', '$konec_tvorby', '$styl');";
          $insert_success = user_db_query($insert_row);
          if(!$insert_success) echo "nepodarilo se vlozit polozku";
          header("Location:vyber_nastroje_skl.php?");
        }
          
          header("Location:aranzer.php");
        }
        /*tahani dat z databaze*/
        
        $vysledek = user_db_query($sql);
        $columns_count = count($skladba["nazvy_sloupcu"]);

      
      echo "<div class=\"skladba\">";
      print_table($skladba, $role, $page);
      echo "</div>";
      

      echo "<div class=\"autor\">";
      print_table($autor, $role, $page);
      echo "</div ";



      echo '</div>
            <!-- formular pro pridani -->
            <div id="P_add_skladba_form" class="abc">
            <!-- Popup Div Starts Here -->
            <div id="popupContact">
            <!-- Contact Us Form -->
            <img id="close" src="img/close-icon.png" onclick ="P_add_form_hide(\'skladba\')">



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
    $add_skladba->addHidden('edit_skladba');
    $add_skladba->addHidden('id');
    $add_skladba->addSubmit('send_skladba', 'Pridat');


  echo $add_skladba; // vykresli formular

  $sub1 = $add_skladba->addContainer('first');

/*
  if ($add_skladba->isSuccess()) {
    echo 'Formuláø byl správnì vyplnìn a odeslán';
      $values = $add_skladba->getValues();
    dump($values);
  }*/

  //vypisuje html kod na dalsich radcich
  echo '
  </div>
  <!-- Popup Div Ends Here -->
  </div>
  <!-- Display Popup Button -->
  <!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->';
  }//uzivatel je prihlasen
 

  echo '
      </div>
      <!-- formular pro pridani -->
      <div id="P_add_autor_form" class="abc">
      <!-- Popup Div Starts Here -->
      <div id="popupContact">
      <!-- Contact Us Form -->
      <img id="close" src="img/close-icon.png" onclick ="P_add_form_hide(\'autor\')">

            <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';

     /**************************************
      Dalsi formular
  ***************************************/
    $add_autor = new Form;
    $add_autor->setAction('aranzer.php');
    $add_autor->setMethod('GET');


    $add_autor->addText('jmeno_autora', 'Jméno autora');
    $add_autor->addText('zacatek_tvorby', 'Začátek tvorby:')
          ->setType('number')
          ->addRule(Form::INTEGER, 'Letopočet musi být číslo');
    $add_autor->addText('konec_tvorby', 'Konec tvorby:')
          ->setType('number')
          ->addRule(Form::INTEGER, 'Letopočet musi být číslo');
    $add_autor->addText('styl', 'Styl');
    
    $add_autor->addHidden('edit_autor');
    $add_autor->addHidden('ID_autora');
    $add_autor->addSubmit('send_autor', 'Pridat');


  echo $add_autor; // vykresli formular

  $sub1 = $add_autor->addContainer('second');



    //vypisuje html kod na dalsich radcich
    echo '
    </div>
    <!-- Popup Div Ends Here -->
    </div>
    <!-- Display Popup Button -->
    <!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->';

  ?>

  </body>
  <script type="text/javascript">
       //pri obnoveni stranky
       $('.autor').hide();
  </script>    

</html>

