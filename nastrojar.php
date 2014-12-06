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
    <title>Nástrojář Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->
  <?php
    // require 'Nette/loader.php';
    // use Nette\Forms\Form;

     include "connect.php";

     $tabulka = "Nastroj";
     $nadpisy_sloupcu = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výměny', 'Vyměněno', 'Výrobní číslo', 'Typ');
     $nazvy_sloupcu = array('datum_vyroby', 'vyrobce','dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
     $pk = "vyrobni_cislo";
     $nadpis_vysledku = "Seznam nástrojů";
     echo "<div id=logout_btn><a href='index.php'>Odhlásit se</a></div>";
     echo '<div id="menu"><ul>';
     // echo "<ul><li><a href='P_add_form_show()'>Pridat zamestnance</a></li>";
     echo "<button onclick='P_add_form_show()'>Přidat nástroj</button>";
     echo "</ul><div>";

  ?>

    <!-- <div id="logout" class="buttons"> </div> -->

    <!-- tabulka se vstupy pro hledani -->
    <table id="hledani" class="pattern">
    <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojů</span>
    <tr>
    <?php
        foreach ($nadpisy_sloupcu as $value) {
          echo "<td>". $value ."</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($nazvy_sloupcu as $value) {
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
        $count=0;
        foreach ($nadpisy_sloupcu as $value) {
          echo "<td class=\"hlavicka\">". $value ."</td>";
          $count++;
        }
        echo "</tr>";

        echo "<tr>";

        //pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkou
        //odstraneni hudebnika z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM ".$tabulka." WHERE ".$pk.'="'.$_GET['delete'].'";';
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
        }
        //pridani radku do tabulky
        
        if(isset($_GET["datum_vyroby"]) and isset($_GET["vyrobce"]) and isset($_GET["dat_posl_revize"])
             and isset($_GET["dat_posl_vymeny"]) and isset($_GET["vymeneno"]) and isset($_GET["vyrobni_cislo"])
              and isset($_GET["ttype"])) {

          $datum_vyroby = $_GET['datum_vyroby'];
          $vyrobce = $_GET['vyrobce'];
          $dat_posl_revize = $_GET['dat_posl_revize'];
          $dat_posl_vymeny = $_GET['dat_posl_vymeny'];
          $vymeneno = $_GET['vymeneno'];
          $vyrobni_cislo = $_GET['vyrobni_cislo'];
          $ttype = $_GET['ttype'];
          
          $insert_row = "INSERT INTO ".$tabulka." VALUES (\"".$datum_vyroby."\", \"".$vyrobce."\", \"".$dat_posl_revize."\",
             \"".$dat_posl_vymeny."\", \"".$vymeneno."\", \"".$vyrobni_cislo."\", \"".$ttype."\");";
          $insert_success = mysql_query($insert_row);
          if(!$insert_success) echo "nepodarilo se vlozit polozku";
        }

        /*tahani dat z databaze*/
        $sql = "select * from ".$tabulka;
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        //vykresleni radku a sloupcu s vysledky
        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) { 
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

  use Nette\Forms\Latin2Form;
  require_once 'Nette/Forms/Latin2Form.php';
  ?>

  <?php
    $form = new Latin2Form;
    $form->setAction('index.php?page=nastrojar.php');
    $form->setMethod('GET');

     $nadpisy_sloupcu = array('', '', '', '', '', '', 'Typ');
     $nazvy_sloupcu = array('', '','', '', '', '', 'ttype');
     

    $form->addText('datum_vyroby', 'Datum výroby')
      ->addRule(Latin2Form::FILLED, 'Zadejte datum výroby');
    $form->addText('vyrobce', 'Výrobce')
      ->addRule(Latin2Form::FILLED, 'Zadejte výrobce');
    $form->addText('dat_posl_revize', 'Datum poslední revize')
      ->addRule(Latin2Form::FILLED, 'Zadejte datum poslední revize');
    $form->addText('dat_posl_vymeny','Datum poslední výměny')
        ->addRule(Latin2Form::FILLED, 'Zadejte datum poslední výměny');
    $form->addText('vymeneno','Vyměněno')
        ->addRule(Latin2Form::FILLED, 'Zadejte, co bylo vyměněno');
    $form->addText('vyrobni_cislo','Výrobní číslo')
        ->addRule(Latin2Form::FILLED, 'Zadejte výrobní číslo');
    $form->addText('ttype','Typ')
        ->addRule(Latin2Form::FILLED, 'Zadejte typ');
    $form->addSubmit('send', 'Přidat');
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
  <!-- ^^^^^^^^^^^^^ Nette Latin2Form  ^^^^^^^^^^^^^ -->

</div>
<!-- Popup Div Ends Here -->
</div>
<!-- Display Popup Button -->
<!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->
    
  </body>
</html>


