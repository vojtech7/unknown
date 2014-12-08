<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <script type="text/javascript" src="netteForms.js"></script>
    <script src="js/libs/jquery-2.1.1.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <title>Aranžér Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    include "connect.php";

    $tabulka = "Koncert";
    $nadpisy_sloupcu = array('ID koncertu', 'Datum a čas', 'Město', 'Adresa');
    $nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
    $pk = "ID_koncertu";
    $nadpis_vysledku = "Seznam koncertů";
    echo '<div id="menu"><ul>';
    echo "<button onclick='P_add_form_show()'>Naplánuj koncert</button>";
    echo "</ul><div>";
    echo "<div id=logout_btn><a href='index.php'>Odhlásit se</a></div>";
    echo '<div id="menu"><ul>';
    echo "</ul><div>";

  ?>

    <!-- tabulka se vstupy pro hledani -->
    <table id="hledani" class="pattern">
    <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání skladeb</span>
    <tr>
    <?php
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
        //odstraneni skladby z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM ".$tabulka_upravy." WHERE ".$pk.'="'.$_GET['delete'].'";';
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
        }
        //pridani radku do tabulky
        if(isset($_GET["nazev"]) and isset($_GET["delka"]) and isset($_GET["jmeno"])) {
          $nazev = $_GET["nazev"];
          $delka = $_GET["delka"];
          $sql = "select max(ID_skladby) from  Skladba";
          $cislo = mysql_fetch_row(mysql_query($sql));
          $ID_skladby = 1 + $cislo[0];

          $ID_autora = $_GET['jmeno']+1;
          
          $insert_row = "INSERT INTO ".$tabulka_upravy." VALUES (\"".$ID_skladby."\", \"".$nazev."\", \"".$delka."\", \"".$ID_autora."\");";
          $insert_success = mysql_query($insert_row);
          if(!$insert_success) echo "nepodarilo se vlozit polozku";
        }

        /*tahani dat z databaze*/
        $sql = "select * from ".$tabulka;
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        //vykresleni radku a sloupcu s vysledky
        //posledni sloupec se vykresluje zvlast,
        //je slozitejsi kvuli datum z jine tabulky
        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) {
            if($i==0) continue;
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
          }
          
          //predam si PK do url parametru delete
          echo "<td id=delete_btn><a href='?page={$_GET['page']}&delete={$row[$pk]}'>Odstranit</a></td>";
          echo "</tr>";
        }

        echo "</tr>";
        echo "</table>";

       ?>

      </div>
  <!-- formular pro pridani -->
  <div id="P_add_form" class="abc">
  <!-- Popup Div Starts Here -->
  <div id="popupContact">
  <!-- Contact Us Form -->
  <img id="close" src="images/3.png" onclick ="P_add_form_hide()">
  <!--
  select {
    background-color: #FDFBFB;
    border: 1px #BBBBBB solid;
    padding: 2px;
    margin: 1px;
    font-size: 14px;
    color: #808080;
  }
  -->

  <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->
  <?php
  require 'Nette/loader.php';

    use Nette\Forms\Form;
    require_once 'Nette/Forms/Form.php';
  ?>

  <?php
    $form = new Form;
    $form->setAction('index.php?page=aranzer.php');
    $form->setMethod('GET');

    $form->addText('nazev', 'Nazev:')
      ->addRule(Form::FILLED, 'Zadejte nazev skladby');
    $form->addText('delka', 'Délka')
      ->addRule(Form::FILLED, 'Zadejte delku skladby');
    $form->addSubmit('send', 'Pridat');
  ?>

  <script src="netteForms.js"></script>
  <style> .required label { color: maroon } </style>

  <?php
  echo $form; // vykresli formular

  $sub1 = $form->addContainer('first');

  if ($form->isSuccess()) {
    echo 'Formulář byl správně vyplněn a odeslán';
      $values = $form->getValues();
    dump($values);
  }

  ?>
  <!-- ^^^^^^^^^^^^^ Nette Form  ^^^^^^^^^^^^^ -->

</div>
<!-- Popup Div Ends Here -->
</div>
<!-- Display Popup Button -->
<!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->
    
  </body>
</html>
