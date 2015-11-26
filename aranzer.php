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
    <title>Aranžér Filharmonie Liptákov</title>
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
        <h3>Přihlášení</h3>
        Login:<input type='text' name='login'><br>
        Heslo:<input type='password' name='heslo'>
        <input type='submit' value='Přihlásit'>         
      </form>";
    }

    //timeout
    // elseif(time() - $_SESSION['timestamp'] > 900) {
    //   session_destroy();
    //   header("Location:timeout.php");
    // }

    else {
     //ziskani jmen autoru pro dalsi praci
    $sql = "select jmeno from Autor";
    $autori = mysql_query($sql);

    for ($i=0; $i < mysql_num_rows($autori); $i++) { 
      $row = mysql_fetch_array($autori, MYSQL_ASSOC);
      $seznam_jmen[$row["jmeno"]] = $row["jmeno"];
    }
    $page = "aranzer.php";
    echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
    echo '<div id="menu"><ul>';
     // echo "<ul><li><a href='P_add_form_show()'>Přidat zaměstnance</a></li>";
    echo "<button onclick='P_add_form_show(\"$role\")'>Přidat skladbu</button>";
    echo "</ul><div>";

    //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojé</span>
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
              $sql = "select max(ID_skladby) from  $tabulka_upravy.";
              $cislo = mysql_fetch_row(mysql_query($sql));
              $ID_skladby = 1 + $cislo[0];

              $sql = "select ID_autora from Autor WHERE jmeno= \"".$_GET["jmeno"]."\"";
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
      $sql = "select ID_skladby, nazev, delka, jmeno from   ".$tabulka_vypis;
      $title = "Seznam skladeb";
      $nadpisy_sloupcu = array('Název', 'Délka [min]', 'Jméno autora');
      $nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'jmeno');
      $ignore = array('ID_skladby');
      $butons = array("edit", "delete");
      $PK = "ID_skladby";
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu, $ignore, $butons, $PK, $role, $page);
      
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

    $add = new Form;
    $add->setAction('index.php?page=aranzer.php');
    $add->setMethod('GET');


    $add->addSelect('jmeno', 'Jmeno autora', $seznam_jmen)
      ->setPrompt( 'Zadejte jméno autora');
    $add->addText('nazev', 'Nazev:')
      ->addRule(Form::FILLED, 'Zadejte nazev skladby');
    $add->addText('delka', 'Délka [min]')
      ->addRule(Form::FILLED, 'Zadejte delku skladby');
    $add->addHidden('edit');
    $add->addHidden('id');
    $add->addSubmit('send', 'Pridat');


  echo $add; // vykresli formular

  $sub1 = $add->addContainer('first');

  if ($add->isSuccess()) {
    echo 'Formulář byl správně vyplněn a odeslán';
      $values = $add->getValues();
    dump($values);
  }

  echo '
  </div>
  <!-- Popup Div Ends Here -->
  </div>
  <!-- Display Popup Button -->
  <!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->';
  }//uzivatel je prihlasen
  ?>
    
  </body>
</html>
