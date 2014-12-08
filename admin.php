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
    <title>Aranžér Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    // include "connect.php";
    include "login.php";
    use Nette\Forms\Form;

    session_start();
    // var_dump($ses_id);
    // echo $_SESSION["rolepage"];
    // echo $_SESSION["time"];
    //uzivatel neni prihlasen
    if(!isset($_SESSION['id'])) {
      echo '
      <form action="login.php?page=admin.php" method="post" enctype="multipart/form-data">
        <h3>Přihlášení</h3>
        Login:<input type="text" name="login"><br>
        Heslo:<input type="password" name="heslo">
        <input type="submit" value="Přihlásit">         
      </form>';
    }

    //uzivatel je prihlasen
    else {
      $tabulka = "Uzivatel";
      $nadpisy_sloupcu = array('Login', 'Role', 'Info');
      $nazvy_sloupcu = array('login', 'heslo_hash', 'role', 'info');
      $pk = "login";
      $nadpis_vysledku = "Seznam uživatelů";
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      echo "<button onclick='P_add_form_show()'>Přidat uživatele</button>";
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
          if($value == "heslo_hash") continue;
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
        //odstraneni skladby z tabulky
        if(isset($_GET['delete'])) {
          $delete_row = "DELETE FROM {$tabulka} WHERE {$pk}='{$_GET['delete']}';";
          // echo "<br><br><br><br>budu mazat: ".$delete_row;
          $delete_success = mysql_query($delete_row);
          if(!$delete_success) echo "nepodarilo se odstranit polozku";
        }
        //pridani radku do tabulky
        if(isset($_GET["login"]) and isset($_GET["heslo"]) and isset($_GET["role"])) {
          $login = $_GET["login"];
          $heslo = $_GET["heslo"];
          $role = $_GET["role"];
          $info = '""';
          if($_GET["info"] != "")
            $info = '"'.$_GET["info"].'"';

          $hash_heslo = sha1($heslo);

          $insert_row = 'INSERT INTO '.$tabulka.' VALUES ("'.$login.'", "'.$hash_heslo.'", "'.$role.'", '.$info.');';
          echo "<br><br><br><br><br>insert_row: $insert_row<br>";
          $insert_success = mysql_query($insert_row);
          if(!$insert_success) echo "nepodarilo se vlozit polozku";
        }

        /*tahani dat z databaze*/
        $sql = "select * from $tabulka";
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        //vykresleni radku a sloupcu s vysledky
        //posledni sloupec se vykresluje zvlast,
        //je slozitejsi kvuli datum z jine tabulky
        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) {
            if($i==1) continue;
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
            <img id="close" src="images/3.png" onclick ="P_add_form_hide()">

            <!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';

    require 'Nette/loader.php';
    require_once 'Nette/Forms/Form.php';
    
    $form = new Form;
    $form->setAction('index.php?page=admin.php');
    $form->setMethod('GET');


    $form->addText('login', 'Login:')
      ->addRule(Form::FILLED, 'Zadejte login');
    $form->addPassword('heslo', 'Heslo:')
      ->addRule(Form::MIN_LENGTH, 'Zadejte heslo', 1)
      ->addRule(Form::MIN_LENGTH,'Heslo musi mit alespon %d znaky', 4);

    $roles = array('manazer', 'personalista', 'hudebnik', 'aranzer', 'nastrojar');
    $form->addSelect('role', 'Role:', $roles)
      ->setItems($roles, FALSE);
    $form->addText('info', 'Rodné číslo pro hudebníka:');
    $form->addSubmit('send', 'Pridat');

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
