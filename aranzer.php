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

     //získání jmen autoru pro dalsí práci
    $sql = "select jmeno from Autor";
    $autori = mysql_query($sql);

    for ($i=0; $i < mysql_num_rows($autori); $i++) { 
      $row = mysql_fetch_array($autori, MYSQL_ASSOC);
      $seznam_jmen[$i] = $row["jmeno"];
    }

    $tabulka_vypis = "Autor natural join Skladba ";
    $tabulka_upravy = "Skladba";
    $nadpisy_sloupcu = array('Název', 'Délka', 'Jméno autora');
    $nazvy_sloupcu = array('nazev', 'delka', 'jmeno');
    $pk = "ID_skladby";
    $nadpis_vysledku = "Seznam skladeb";
    echo "<div id=logout_btn><a href='index.php'>Odhlásit se</a></div>";
    echo '<div id="menu"><ul>';
     // echo "<ul><li><a href='P_add_form_show()'>Přidat zaměstnance</a></li>";
    echo "<button onclick='P_add_form_show()'>Přidat skladbu</button>";
    echo "</ul><div>";

  ?>

    <!-- <div id="logout" class="buttons"> </div> -->

    <!-- tabulka se vstupy pro hledani -->
    <table id="hledani" class="pattern">
    <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání skladeb</span>
    <tr>
    <?php
        foreach ($nadpisy_sloupcu as $value) {
          echo "<td>". $value ."</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($nazvy_sloupcu as $value) {
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
        $sql = "select ID_skladby, nazev, delka, jmeno from   ".$tabulka_vypis;
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        //vykresleni radku a sloupcu s vysledky
        //posledni sloupec se vykresluje zvlast,
        //je slozitejsi kvuli datum z jine tabulky
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
    $form->setAction('index.php?page=aranzer.php');
    $form->setMethod('GET');


    $form->addSelect('jmeno', 'Jméno autora', $seznam_jmen)
      ->setPrompt( 'Zadejte jméno autora');
    $form->addText('nazev', 'Název:')
      ->addRule(Latin2Form::FILLED, 'Zadejte název skladby');
    $form->addText('delka', 'Délka')
      ->addRule(Latin2Form::FILLED, 'Zadejte délku skladby');
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
