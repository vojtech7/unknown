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

     //získání jmen autorů pro dalsí práci
    $sql = "select jmeno, prijmeni from Autor";
    $autori = mysql_query($sql);

    for ($i=0; $i < mysql_num_rows($autori); $i++) { 
      $row = mysql_fetch_array($autori, MYSQL_ASSOC);
      $seznam_jmen[$i] = $row["jmeno"]."  ".$row["prijmeni"];
    }

    $tabulka = "Skladba";
    $nadpisy_sloupcu = array('ID skladby', 'Název', 'Délka', 'Jméno autora');
    $nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'ID_autora');
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
          if ($value !== "ID_autora") {
             echo "<td> <input type=\"text\" class=\"form-control filter_". $value ."\"></td>";
            }
          else
            { echo "<td><select class=\"filter_predpis form-control\">";
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
        //odstraneni hudebnika z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM ".$tabulka." WHERE ".$pk.'="'.$_GET['delete'].'";';
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
        }
        //pridani radku do tabulky
        if(isset($_GET["jmeno"]) and isset($_GET["prijmeni"]) and isset($_GET["rodne_cislo"])) {
          $jmeno = $_GET["jmeno"];
          $prijmeni = $_GET["prijmeni"];
          $rodne_cislo = $_GET["rodne_cislo"];
          $insert_row = "INSERT INTO ".$tabulka." VALUES (\"".$rodne_cislo."\", \"".$jmeno."\", \"".$prijmeni."\");";
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
          for ($i=0; $i < $columns_count-1; $i++) { 
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
          }
          $dotaz = "select jmeno, prijmeni from Autor where ID_autora=".$row[3];
          $jmeno = mysql_fetch_array(mysql_query($dotaz));
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>".$jmeno[0]." ".$jmeno[1]."</td>";

          //predam si PK do url parametru delete
          echo "<td id=delete_btn><a href='#?page={$_GET['page']}&delete={$row[$pk]}'>Odstranit</a></td>";
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
    $form->setAction('index.php?page=personalista.php');
    $form->setMethod('GET');

    $form->addText('jmeno', 'Jméno:')
      ->addRule(Latin2Form::FILLED, 'Zadejte jméno');
    $form->addText('prijmeni', 'Příjmení:')
      ->addRule(Latin2Form::FILLED, 'Zadejte příjmení');
    $form->addText('rodne_cislo', 'Rodné číslo:')
      ->addRule(Latin2Form::FILLED, 'Zadejte rodné číslo');
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
