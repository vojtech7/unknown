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
    use Nette\Forms\Form;

    session_start();
    $role = 'nastrojar';
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
      $tabulka_uprav = "Nastroj";
      $nadpisy_sloupcu = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmìny', 'Vymìnìno', 'Výrobní èíslo', 'Typ');
      $nazvy_sloupcu = array('datum_vyroby', 'vyrobce','dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
      $pk = "vyrobni_cislo";
      $nadpis_vysledku = "Seznam nástrojù";
      $page = "nastrojar.php";
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      // echo "<ul><li><a href='P_add_form_show()'>Pridat zamestnance</a></li>";
      echo "<button onclick='P_add_form_show(\"$role\")'>Přidat nástroj</button>";
      echo "</ul><div>";


    //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojù</span>
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
        //odstraneni nastroje z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM ".$tabulka_uprav." WHERE ".$pk.'="'.$_GET['delete'].'";';
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
          header("Location:nastrojar.php");
        }
        
        //upraveni nastroje z tabulky
        if(isset($_GET['alter'])) {

        	$alter_row = "SELECT FROM ".$tabulka_uprav." WHERE ".$pk.'="'.$_GET['alter'].'";';
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
        //pridani nebo uprava radku tabulky
        
        if(isset($_GET["datum_vyroby"]) and isset($_GET["vyrobce"]) and isset($_GET["dat_posl_revize"])
             and isset($_GET["dat_posl_vymeny"]) and isset($_GET["vymeneno"]) and isset($_GET["vyrobni_cislo"])
              and isset($_GET["ttype"]) and isset($_GET["edit"]) and isset($_GET["PK_old"])) {
        
          $vyrobce = $_GET['vyrobce'];
          $datum_vyroby = $_GET['datum_vyroby'] !="" ? "STR_TO_DATE('".$_GET['datum_vyroby']."', '%d.%m.%Y')" : "null";
          $dat_posl_revize = $_GET['dat_posl_revize']!="" ? "STR_TO_DATE('".$_GET['dat_posl_revize']."', '%d.%m.%Y')" : "null";
          $dat_posl_vymeny = $_GET['dat_posl_vymeny']!="" ? "STR_TO_DATE('".$_GET['dat_posl_vymeny']."', '%d.%m.%Y')" : "null";
          $vymeneno = $_GET['vymeneno'];
          $vyrobni_cislo = $_GET['vyrobni_cislo'];
          $ttype = $_GET['ttype'];
          
          if ($_GET["edit"]=="edit") {
		        //upravujeme radek
            $PK_old=$_GET["PK_old"];
  	 		    $sql="UPDATE $tabulka_uprav SET datum_vyroby = $datum_vyroby, vyrobce ='$vyrobce', dat_posl_revize=$dat_posl_revize,
               dat_posl_vymeny = $dat_posl_vymeny, vymeneno ='$vymeneno', ttype = '$ttype', vyrobni_cislo = '$vyrobni_cislo' 
               WHERE vyrobni_cislo = '$PK_old'";
          }
          elseif ($_GET["edit"]=="add") {
      			$sql = "INSERT INTO $tabulka_uprav VALUES ($datum_vyroby, '$vyrobce', $dat_posl_revize,
      			$dat_posl_vymeny, '$vymeneno', '$vyrobni_cislo', '$ttype');"; 
          }
          // $sql = "INSERT INTO $tabulka_uprav VALUES (NULL, '$vyrobce', NULL, NULL, '$vymeneno', '$vyrobni_cislo', '$ttype');";
          //echo 'edit je: '.$_GET["edit"];
          echo $sql;
          $insert_success = mysql_query($sql);
      		if(!$insert_success) echo "nepodarilo se vlozit polozku";
      		header("Location:nastrojar.php");
        }

        /*tahani dat z databaze*/
        $sql = "select * from $tabulka_uprav";
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        /*
          $alter = hodnoty všech sloupců tabulky oddělené vlnovkou ~~
          předává se do formuláře pro úpravu skladby
        */
        $alter="";
        

        //vykresleni radku a sloupcu s vysledky
        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) { 
            if($i==0 or $i==2 or $i==3) {  // datumy
              if ($row[$i]==null) {
                $mydate="---";
                $alter=$alter."~~";
              }
              else{
                $date = date_create($row[$i]);
                $mydate = date_format($date, "d.m.Y");
                $alter = $alter.$mydate."~~";
              }
              echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$mydate}</td>";
            }
            else {
              echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
              $alter = $alter.$row[$i]."~~";
            }
          }
          //predam si PK do url parametru delete
          echo "<td id=delete_btn><a href='?page={$page}&delete={$row[$pk]}'>Odstranit</a></td>";
          $alter="\"".$alter."\"";
          echo "<td class=alter_btn><button onclick='P_alter_form_show($alter, \"$role\")'>Upravit</button></td>";
          $alter="";
          echo "</tr>";
        }

        echo "</tr>";
        echo "</table>";

      //ziskani typu nastroju pro select ve formulari
      $sql = "select * from Typ";
      $typy = mysql_query($sql);

      for ($i=0; $i < mysql_num_rows($typy); $i++) { 
        $row = mysql_fetch_array($typy, MYSQL_ASSOC);
        $seznam_typu[$row["ttype"]] = $row["ttype"];
      }


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
     
    function dateType($item, $arg) {
      return true;
    }

    $form->addSelect('ttype', 'Zadejte typ', $seznam_typu);
    $form->addText('vyrobce', 'Výrobce')
      ->addRule(Form::FILLED, 'Zadejte vyrobce');
    $form->addText('vyrobni_cislo','Vyrobni cislo')
        ->addRule(Form::FILLED, 'Zadejte vyrobni cislo');
    $form->addText('datum_vyroby', 'Datum výroby')
         ->setAttribute('placeholder', 'dd.mm.rrrr')
         ->addRule('dateType', 'Zadejte platne datum ve formatu dd.mm.rrrr');
    $form->addText('dat_posl_revize', 'Datum poslední revize')
         ->setAttribute('placeholder', 'dd.mm.rrrr')
         ->addRule('dateType', 'Zadejte platne datum ve formatu dd.mm.rrrr');
    $form->addText('dat_posl_vymeny','Datum poslední výmìny')
         ->setAttribute('placeholder', 'dd.mm.rrrr')
         ->addRule('dateType', 'Zadejte platne datum ve formatu dd.mm.rrrr');
    $form->addText('vymeneno','Vymìnìno');
    $form->addHidden('edit');
    $form->addHidden('PK_old');
    $form->addSubmit('send', 'Přidat');

  echo $form; // vykresli formular

  $sub1 = $form->addContainer('first');

  if ($form->isSuccess()) {
    echo 'Formulář byl správnì vyplnìn a odeslán';
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


