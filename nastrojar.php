<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <script type="text/javascript" src="js/netteForms.js"></script>
    <script src="js/dateValidator.js"></script>
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
    // use Nette\Forms\Form;
    include "connect.php";
    include 'functions.php';
    use Nette\Forms\Form;

    session_save_path("./tmp");
    session_start();
    $role = 'nastrojar';
    $page = 'nastrojar.php';    
    //uzivatel neni prihlasen
    //if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
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

    //uzivatel je prihlasen, tohle else je az do konce souboru
    else {

      $_SESSION['timestamp'] = time();
      $nastroj = array();
      $nastroj["nadpisy_sloupcu"] = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmìny', 'Vymìnìno', 'Výrobní èíslo', 'Typ');
      $nastroj["nazvy_sloupcu"] = array('datum_vyroby', 'vyrobce','dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
      $nastroj["tabulka_uprav"] = "Nastroj";
      $nastroj["ignore"] = null;
      $nastroj["sql"] = "SELECT * 
                         FROM Nastroj";
      $nastroj["title"] = "Seznam nástrojů";
      $nastroj["buttons"] = array("edit", "delete");
      $nastroj["PK"] = "vyrobni_cislo";


      $typ = array();
      $typ["nadpisy_sloupcu"] = array("Typ");
      $typ["nazvy_sloupcu"] = array("ttype");
      $typ["tabulka_uprav"] = "Typ";
      $typ["ignore"] = null;
      $typ["sql"] = "SELECT *
                     FROM Typ";
      $typ["PK"] = "ttype";
      $typ["title"] = "Seznam typů";
      $typ["buttons"] = array("delete");

      

      /***********************************
          ZAČÁTEK OBSAHU STRÁNKY
      ***********************************/
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      // echo "<ul><li><a href='P_add_form_show()'>Pridat zamestnance</a></li>";
      echo "<button class=\"nastroj\" onclick='P_add_form_show(\"$role\", \"nastroj\")'>Přidat nástroj</button><br>";
      echo "<button class=\"typ\" onclick='P_add_form_show(\"$role\", \"typ\")'>Přidat typ nástroje</button><br>";
      echo "<button onclick='switch_table(\"nastroj\")'>Zobrazit nástroje</button><br>";
      echo "<button onclick='switch_table(\"typ\")'>Zobrazit typy nástrojů</button>";
      echo "<div class=switch_btn><a href='?sklad=sklad'>Na skladě</a></div>";
      echo "<div class=switch_btn><a href='?sklad=pujcka'>Vypůjčené</a></div>";
      echo "<div class=switch_btn><a href='?sklad=vse'>Všechny nástroje</a></div>";
      echo "</ul><div>";


    //tabulka se vstupy pro hledani
        echo "<div class=\"nastroj\">";
         print_search_table($nastroj["nadpisy_sloupcu"], $nastroj["nazvy_sloupcu"], "nastroj", $nastroj["ignore"]);
        echo "</div>";
        echo "<div class=\"typ\">"; 
          print_search_table($typ["nadpisy_sloupcu"], $typ["nazvy_sloupcu"], "typ", $typ["ignore"]);
        echo "</div>";
        
        echo "<tr>";
          
        //pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkou
        //odstraneni nastroje z tabulky
        if(isset($_GET['delete']) and isset($_GET["tabulka"])) {
          if ($_GET["tabulka"]=="Typ") {
            $pk = "ttype";
          }
          else
              $pk = "vyrobni_cislo";
          $delete_row = "DELETE FROM {$_GET["tabulka"]} 
                         WHERE $pk='{$_GET["delete"]}';";
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
          header("Location:nastrojar.php");
        }
        
        //upraveni nastroje z tabulky
        if(isset($_GET['alter'])) {

        	$alter_row = "SELECT FROM $tabulka_uprav WHERE $pk = '".$_GET["alter"]."';";
        	$alter_success = mysql_query($alter_row);
        	if (!$alter_success) {
        		echo $_GET['alter'];
        	}
        	else{
        		$alter_row = mysql_fetch_array($alter_row, MYSQL_ASSOC);
        		$form->setDefaults(array(
        		    'datum_vyroby' => $alter_row['datum_vyroby'],
        		    'vyrobce' => $alter_row['vyrobce'],
        		    'dat_posl_revize' => $alter_row['dat_posl_revize'],
        		    'vymeneno' => $alter_row['vymeneno'],
        		    'vyrobni_cislo' => $alter_row['vyrobni_cislo'],
        		    'ttype' => $alter_row['ttype']
        		));
        	}
        }
        //pridani nebo uprava radku tabulky Nastroj
        
        if(isset($_GET["datum_vyroby"]) and isset($_GET["vyrobce"]) and isset($_GET["dat_posl_revize"])
             and isset($_GET["dat_posl_vymeny"]) and isset($_GET["vymeneno"]) and isset($_GET["vyrobni_cislo"])
              and isset($_GET["ttype"]) and isset($_GET["edit"]) and isset($_GET["PK_old"])) {
          $vyrobce = $_GET['vyrobce'];
          $datum_vyroby = $_GET['datum_vyroby'] !="" ? "STR_TO_DATE('".$_GET['datum_vyroby']."', '%d.%m.%Y')" : "null";
          $dat_posl_revize = $_GET['dat_posl_revize'] != "" ? "STR_TO_DATE('".$_GET['dat_posl_revize']."', '%d.%m.%Y')" : "null";
          $dat_posl_vymeny = $_GET['dat_posl_vymeny'] != "" ? "STR_TO_DATE('".$_GET['dat_posl_vymeny']."', '%d.%m.%Y')" : "null";
          $vymeneno = $_GET['vymeneno'] != "" ? $_GET['vymeneno'] : null;
          $vyrobni_cislo = $_GET['vyrobni_cislo'];
          $ttype = $_GET['ttype'];
          
          if($_GET["edit"]=="edit") {
		        //upravujeme radek
            $PK_old=$_GET["PK_old"];
  	 		    $sql="UPDATE Nastroj SET datum_vyroby = $datum_vyroby, vyrobce ='$vyrobce', dat_posl_revize=$dat_posl_revize,
               dat_posl_vymeny = $dat_posl_vymeny, vymeneno = '$vymeneno', ttype = '$ttype', vyrobni_cislo = '$vyrobni_cislo', rodne_cislo = null
               WHERE vyrobni_cislo = '$PK_old'";
          }
          elseif($_GET["edit"]=="add") {
      			$sql = "INSERT INTO Nastroj VALUES ($datum_vyroby, '$vyrobce', $dat_posl_revize,
      			$dat_posl_vymeny, '$vymeneno', '$vyrobni_cislo', '$ttype', null);"; 
          }
           //$sql = "INSERT INTO {$nastroj["tabulka_uprav"]} VALUES (NULL, '$vyrobce', NULL, NULL, '$vymeneno', '$vyrobni_cislo', '$ttype', NULL);";
          //echo 'edit je: '.$_GET["edit"];
          echo $sql;
          $insert_success = mysql_query($sql);
      		if(!$insert_success) echo "nepodarilo se vlozit polozku: ".mysql_error();
      		header("Location:nastrojar.php");
        }

        //pridavani radku Typ
        if (isset($_GET["send_typ"]) and isset($_GET["typ"])) {
          $sql = "INSERT INTO Typ VALUES ('{$_GET["typ"]}');";
          $insert_success = mysql_query($sql);
          if(!$insert_success)
            echo "nepodarilo se vlozit polozku: ".mysql_error();
          else {
            //pridani polozek do tabulky Hraje_v
            $sql = "SELECT ID_skladby
                    FROM Skladba;";
            $skladby = mysql_query($sql);
            if($skladby) {
              while ($skladba = mysql_fetch_array($skladby, MYSQL_ASSOC)) {
                $sql = "INSERT INTO Hraje_v
                        VALUES ('{$_GET["typ"]}', {$skladba["ID_skladby"]}, 0)";
                mysql_query($sql);
              }
            }
          }
          header("Location:nastrojar.php"); 
        }
        /*tahani dat z databaze*/
        if (isset($_GET["sklad"]) && $_GET["sklad"]=="sklad")
          $nastroj["sql"] = "SELECT * FROM {$nastroj["tabulka_uprav"]}
                  WHERE rodne_cislo IS NULL;";
        elseif (isset($_GET["sklad"]) && $_GET["sklad"]=="pujcka")
          $nastroj["sql"] = "SELECT * FROM {$nastroj["tabulka_uprav"]}
                  WHERE rodne_cislo IS NOT NULL;";
        else 
          $nastroj["sql"] = "SELECT * FROM {$nastroj["tabulka_uprav"]}";
          
          
       
        /*
          $alter = hodnoty všech sloupců tabulky oddělené vlnovkou ~~
          předává se do formuláře pro úpravu skladby
        */
        $alter="";
      
        echo "<div class=\"nastroj\">";
        print_table($nastroj, $role, $page);
        echo "</div>";
        
        echo "<div class=\"typ\">";
        print_table($typ, $role, $page);
        echo "</div>";
              
      //ziskani typu nastroju pro select ve formulari
      $sql = "SELECT * FROM Typ";
      $typy = mysql_query($sql);

      for ($i=0; $i < mysql_num_rows($typy); $i++) { 
        $row = mysql_fetch_array($typy, MYSQL_ASSOC);
        $seznam_typu[$row["ttype"]] = $row["ttype"];
      }


      echo '</div>
            <!-- formular pro pridani -->
            <div id="P_add_nastroj_form" class="abc">
            <!-- Popup Div Starts Here -->
            <div id="popupContact">
            <!-- Contact Us Form -->
            <img id="close" src="img/close-icon.png" onclick ="P_add_form_hide(\'nastroj\')">

            <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';
  require 'Nette/loader.php';

  require_once 'Nette/Forms/Form.php';

    $form = new Form;
    $form->setAction('nastrojar.php');
    $form->setMethod('GET');

     $nadpisy_sloupcu = array('', '', '', '', '', '', 'Typ');
     $nazvy_sloupcu = array('', '','', '', '', '', 'ttype');
     
    function dateType($item, $arg) {
      return true;
    }

    $form->addSelect('ttype', 'Zadejte typ', $seznam_typu);
    $form->addText('vyrobce', 'Výrobce')
      ->addRule(Form::FILLED, 'Zadejte vyrobce');
    $form->addText('vyrobni_cislo','Vyrobni cislo')
        ->addRule(Form::FILLED, 'Zadejte vyrobni cislo');
    $form->addText('datum_vyroby', 'Datum výroby')
         ->setAttribute('placeholder', 'DD.MM.RRRR')
         ->addRule('dateType', 'Zadejte platne datum ve formatu DD.MM.RRRR');
    $form->addText('dat_posl_revize', 'Datum poslední revize')
         ->setAttribute('placeholder', 'DD.MM.RRRR')
         ->addRule('dateType', 'Zadejte platne datum ve formatu DD.MM.RRRR');
    $form->addText('dat_posl_vymeny','Datum poslední výmìny')
         ->setAttribute('placeholder', 'DD.MM.RRRR')
         ->addRule('dateType', 'Zadejte platne datum ve formatu DD.MM.RRRR');
    $form->addText('vymeneno','Vymìnìno');
    $form->addHidden('edit', "add");
    $form->addHidden('PK_old');
    $form->addSubmit('send', 'Přidat');

  echo $form; // vykresli formular

  $sub1 = $form->addContainer('first');
/*
  if ($form->isSuccess()) {
    echo 'Formulář byl správnì vyplnìn a odeslán';
      $values = $form->getValues();
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
      /**************************************
           Dalsi formular
      ***************************************/
 echo '
      </div>
      <!-- formular pro pridani -->
      <div id="P_add_typ_form" class="abc">
      <!-- Popup Div Starts Here -->
      <div id="popupContact">
      <!-- Contact Us Form -->
      <img id="close" src="img/close-icon.png" onclick ="P_add_form_hide(\'typ\')">

            <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';
           
    $add_typ = new Form;
    $add_typ->setAction('nastrojar.php');
    $add_typ->setMethod('GET');


    $add_typ->addText('typ', 'Typ')
            ->addRule(Form::FILLED, 'Zadejte typ nástroje');

  /*  
    $add_typ->addHidden('edit_typ');
    $add_typ->addHidden('ID_typa');
  */
    $add_typ->addSubmit('send_typ', 'Pridat');

    echo $add_typ; // vykresli formular

    $sub1 = $add_typ->addContainer('second');

    if ($add_typ->isSuccess()) {
      echo 'Formuláø byl správnì vyplnìn a odeslán';
        $values = $add_typ->getValues();
      dump($values);
    }



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
       $('.typ').hide();
  </script>    

</html>


