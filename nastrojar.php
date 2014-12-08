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
    <style> .required label { color: maroon } </style>
    <title>Nástrojář Filharmonie Liptákov</title>
  </head>
<body>
  <!-- uvodni inicializace -->
  <?php
    // require 'Nette/loader.php';
    include "connect.php";
    use Nette\Forms\Form;

    session_start();
    $ses_id = session_id();
    //uzivatel neni prihlasen
    if(!isset($_SESSION['id'])) {
      echo '
      <form action="login.php?page=nastrojar.php" method="post" enctype="multipart/form-data">
        <h3>Přihlášení</h3>
        Login:<input type="text" name="login"><br>
        Heslo:<input type="password" name="heslo">
        <input type="submit" value="Přihlásit">         
      </form>';
    }

    //uzivatel je prihlasen, tohle else je az do konce souboru
    else {
      $tabulka = "Nastroj";
      $nadpisy_sloupcu = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výměny', 'Vyměněno', 'Výrobní číslo', 'Typ');
      $nazvy_sloupcu = array('datum_vyroby', 'vyrobce','dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
      $pk = "vyrobni_cislo";
      $nadpis_vysledku = "Seznam nástrojů";
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      // echo "<ul><li><a href='P_add_form_show()'>Pridat zamestnance</a></li>";
      echo "<button onclick='P_add_form_show()'>Přidat nástroj</button>";
      echo "</ul><div>";



    //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojů</span>
            <tr>';
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
    $form->setAction('index.php?page=nastrojar.php');
    $form->setMethod('GET');

     $nadpisy_sloupcu = array('', '', '', '', '', '', 'Typ');
     $nazvy_sloupcu = array('', '','', '', '', '', 'ttype');
     
    $sql = "select * from Typ";
    $typy = mysql_query($sql);

    for ($i=0; $i < mysql_num_rows($typy); $i++) { 
      $row = mysql_fetch_array($typy);
      $seznam_typu[$i] = $row["ttype"];
    }

    $form->addSelect('ttype','Typ', $seznam_typu)
         ->setPrompt('Zadejte typ nástroje');
    $form->addText('datum_vyroby', 'Datum výroby')
      ->addRule(Form::FILLED, 'Zadejte datum vyroby')
      ->setAttribute('placeholder', 'rrrr-mm-dd');
    $form->addText('vyrobce', 'Výrobce')
      ->addRule(Form::FILLED, 'Zadejte vyrobce');
    $form->addText('dat_posl_revize', 'Datum poslední revize')
      ->addRule(Form::FILLED, 'Zadejte datum posledni revize')
      ->setAttribute('placeholder', 'rrrr-mm-dd');
    $form->addText('dat_posl_vymeny','Datum poslední výměny')
        ->addRule(Form::FILLED, 'Zadejte datum posledni vymeny')
        ->setAttribute('placeholder', 'rrrr-mm-dd');
    $form->addText('vymeneno','Vyměněno')
        ->addRule(Form::FILLED, 'Zadejte, co bylo vymeneno');
    $form->addText('vyrobni_cislo','Výrobní číslo')
        ->addRule(Form::FILLED, 'Zadejte vyrobni cislo');
    $form->addSubmit('send', 'Přidat');

  echo $form; // vykresli formular

  $sub1 = $form->addContainer('first');

  if ($form->isSuccess()) {
    echo 'Formulář byl správně vyplněn a odeslán';
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


