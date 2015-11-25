<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link href="css/form.css" rel="stylesheet">
    <meta charset="iso-8859-2">
    <script type="text/javascript" src="js/netteForms.js"></script>
    <script src="js/libs/jquery-2.1.1.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <title>Hudebník Filharmonie Liptákov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    include "connect.php";

    session_start();
    $role = 'hudebnik';
    //uzivatel neni prihlasen
    if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
    // if(0){
      echo "
      <form action='login.php?page=$role.php' method='post' enctype='multipart/form-data'>
        <h3>Přihlášení</h3>
        Rodné číslo:<input type='text' name='rodne_cislo'><br>
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
      $tabulka = "Koncert";
      $nadpisy_sloupcu = array('ID koncertu', 'Název Koncertu', 'Datum a èas', 'Mìsto', 'Adresa');
      $nazvy_sloupcu = array('ID_koncertu', 'nazev_koncertu', 'datum_a_cas', 'mesto', 'adresa');
      $pk = "ID_koncertu";
      $nadpis_vysledku = "Seznam koncertù";
      echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
      echo '<div id="menu"><ul>';
      //echo "<button onclick='P_add_form_show(\"$role\")'>Zobraz nejbližší koncert</button>";
      echo "<div class=switch_btn><a href='?moje=true'>Moje koncerty</a></div>";
      echo "<div class=switch_btn><a href='?moje=false'>Všechny koncerty</a></div>";
      echo "</ul><div>";
 

      // tabulka se vstupy pro hledani
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
          /*tahani dat z databaze*/
          if (isset($_GET["moje"]) && $_GET["moje"]=="true")
            $sql = "select * from $tabulka NATURAL JOIN Vystupuje_na
                    WHERE rodne_cislo ='".$_SESSION["user_login"]."';";
          else 
            $sql = "select * from $tabulka";
          
          $vysledek = mysql_query($sql);
          $columns_count = count($nazvy_sloupcu);

          while($row = mysql_fetch_array($vysledek)){
            echo "<tr>";
            for ($i=0; $i < $columns_count; $i++) {
              if($i==0) continue;  //ID koncertu
              if($i==2) {  // datum
                $date = date_create($row[$i]);
                $mydate = date_format($date, "d.m.Y H:i");
                echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$mydate}</td>";
              }
              else {
                echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
              }
            }
            echo "</tr>";
          }

          echo "</tr>";
          echo "</table>";
        }
       ?>

      </div>
  </body>
</html>