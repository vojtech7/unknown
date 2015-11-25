<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <script type="text/javascript" src="js/netteForms.js"></script>
    <script src="js/datetimeValidator.js"></script>
    <script src="js/libs/jquery-2.1.1.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <style> .required label { color: maroon } </style>
    <title>Mana¾er Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    include "connect.php";
    use Nette\Forms\Form;

    session_start();
    $role = 'manazer';
    //uzivatel neni prihlasen
    //if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
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

    //uzivatel je prihlasen, tohle else je az do konce souboru
    else {
      $_SESSION['timestamp'] = time();
      $tabulka_uprav = "Koncert";
      $nadpisy_sloupcu = array('ID koncertu', 'Název Koncertu', 'Datum a èas', 'Mìsto', 'Adresa');
      $nazvy_sloupcu = array('ID_koncertu', 'nazev_koncertu', 'datum_a_cas', 'mesto', 'adresa');
      $pk = "ID_koncertu";
      $nadpis_vysledku = "Seznam koncertù";
      $page = $role.".php";
      echo '<div id="menu"><ul>';
      echo "<button onclick='P_add_form_show(\"$role\")'>Naplánuj koncert</button>";
      echo "</ul><div>";
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      echo "</ul><div>";


    //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojù</span>
            <tr>';
        foreach ($nadpisy_sloupcu as $value) {
          if ($value === "ID koncertu") continue; 
          echo "<td>". $value ."</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($nazvy_sloupcu as $value) {
          if ($value === "ID_koncertu") continue; 
        echo "<td> <input type=\"text\" class=\"form-control filter_". $value ."\"></td>";
        }

        echo "</tr>";
        echo "</table>";

        //tabulka pro zobrazovani vysledku hledani
        echo '<table id="prehled" class="data">';
        echo '<span class="nadpis" id="nadpis_vysledku">';
        echo $nadpis_vysledku;
        echo "</span>";

        echo "<tr>";
        foreach ($nadpisy_sloupcu as $value) {
          if ($value === "ID koncertu") continue; 
          echo "<td class=\"hlavicka\">". $value ."</td>";
        }
        echo "</tr>";

        echo "<tr>";

        //pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkou
        //odstraneni koncertu z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM ".$tabulka_uprav." WHERE ".$pk.'="'.$_GET['delete'].'";';
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
          header("Location:manazer.php");
        }
        //pridani nebo uprava radku tabulky
        if(isset($_GET["nazev_koncertu"]) and isset($_GET["mesto"]) and isset($_GET["adresa"]) and isset($_GET["datum_a_cas"]) and isset($_GET["edit"])) {
          $nazev_koncertu = $_GET["nazev_koncertu"];
          $datum_a_cas = $_GET["datum_a_cas"];
          $mesto = $_GET["mesto"];
          $adresa = $_GET["adresa"];
          if ($_GET["edit"]=="edit") {
            // upravuje se radek
            $ID_koncertu = $_GET["id"];
            $sql="UPDATE $tabulka_uprav SET nazev_koncertu='$nazev_koncertu', datum_a_cas = STR_TO_DATE('$datum_a_cas', '%d.%m.%Y %H:%i'), mesto ='$mesto', adresa='$adresa' WHERE ID_koncertu =$ID_koncertu";
            $update_success = mysql_query($sql);
            if(!$update_success) echo "nepodarilo se upravit polozku";
          }
          elseif ($_GET["edit"]=="add") {
            //pridava se radek
            $sql = "select max(ID_koncertu) from  Koncert";
            $cislo = mysql_fetch_row(mysql_query($sql));
            $ID_koncertu = 1 + $cislo[0];

            // datum ve formatu "dd.mm.rrrr hh:mm"
            $insert_row = "INSERT INTO $tabulka_uprav VALUES ($ID_koncertu, \"$nazev_koncertu\", STR_TO_DATE('$datum_a_cas', '%d.%m.%Y %H:%i'), \"$mesto\", \"$adresa\");";
            // echo $insert_row;
            $insert_success = mysql_query($insert_row);
            if(!$insert_success) echo "nepodarilo se vlozit polozku";
            header("Location:vyber_skladby_kon.php?id_kon=$ID_koncertu");
          }
          // header("Location:manazer.php");
        }

        /*tahani dat z databaze*/
        $sql = "select * from ".$tabulka_uprav;
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);
        /*
          $alter = hodnoty všech sloupců tabulky oddělené vlnovkou ~
          předává se do formuláře pro úpravu skladby
        */
        $alter="";
        
        //vykresleni radku a sloupcu s vysledky
        //posledni sloupec se vykresluje zvlast,
        //je slozitejsi kvuli datum z jine tabulky
        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) {
            if($i==0) {   //ID_koncertu
              $alter = $alter.$row[$i]."~~";
              continue;
            }
            else if($i==1) {  // jmeno koncertu 
              echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='koncert.php?id_kon={$row[0]}'>{$row[$i]}</a></td>";
              $alter = $alter.$row[$i]."~~";
            }
            else if($i==2) {  // datum koncertu
              $date = date_create($row[$i]);
              $mydate = date_format($date, "d.m.Y H:i");
              echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$mydate}</td>";
              $alter = $alter.$mydate."~~";
            }
            else {
              echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
              $alter = $alter.$row[$i]."~~";
            }
          }
          
          //predam si PK do url parametru delete
          echo "<td id=delete_btn><a href='?page={$page}&delete={$row[$pk]}'>Odstranit</a></td>";
          //dám $alter do uvozovek
          $alter="\"".$alter."\"";
          echo "<td class=alter_btn><button onclick='P_alter_form_show($alter, \"$role\")'>Upravit</button></td>";
          $alter="";

          echo "</tr>";
        }

        echo "</tr>";
        echo "</table>";

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

    $form = new Form;
    $form->setAction('index.php?page=manazer.php');
    $form->setMethod('GET');

    function datetimeType($item, $arg) {
      return true;
    }

    $form->addText('nazev_koncertu', 'Nazev koncertu:')
      ->addRule(Form::FILLED, 'Zadejte nazev koncertu');
    $form->addText('mesto', 'Mesto:')
      ->addRule(Form::FILLED, 'Zadejte mesto, ve kterem bude koncert');
    $form->addText('adresa', 'Adresa')
      ->addRule(Form::FILLED, 'Zadejte adresu koncertu');
    $form->addText('datum_a_cas', 'Datum a cas')
      ->setAttribute('placeholder', 'dd.mm.rrrr hh:mm')
      ->addRule('datetimeType', 'Zadejte platne datum a cas ve formatu dd.mm.rrrr hh:mm')
      ->addRule(Form::FILLED, 'Zadejte datum a cas koncertu');
    $form->addHidden('edit');
    $form->addHidden('id');
    $form->addSubmit('send', 'Pridat');

  echo $form; // vykresli formular

  $sub1 = $form->addContainer('first');

  if ($form->isSuccess()) {
    echo 'Formuláø byl správnì vyplnìn a odeslán';
      $values = $form->getValues();
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
