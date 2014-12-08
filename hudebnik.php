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
    <title>Hudebn�k Filharmonie Lipt�kov</title>
  </head>
<body>

  <!-- uvodni inicializace -->

  <?php  
    include "connect.php";

    $tabulka = "Koncert";
    $nadpisy_sloupcu = array('ID koncertu', 'Datum a �as', 'M�sto', 'Adresa');
    $nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
    $pk = "ID_koncertu";
    $nadpis_vysledku = "Seznam koncert�";
    echo "<div id=logout_btn><a href='index.php'>Odhl�sit se</a></div>";
    echo '<div id="menu"><ul>';
    echo "<button onclick='P_add_form_show()'>Zobraz nejbli��� koncert</button>";
    echo "</ul><div>";

  ?>

    <!-- <div id="logout" class="buttons"> </div> -->

    <!-- tabulka se vstupy pro hledani -->
    <table id="hledani" class="pattern">
    <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhled�v�n� skladeb</span>
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
        /*tahani dat z databaze*/
        $sql = "select * from ".$tabulka;
        
        $vysledek = mysql_query($sql);
        $columns_count = count($nazvy_sloupcu);

        while($row = mysql_fetch_array($vysledek)){
          echo "<tr>";
          for ($i=0; $i < $columns_count; $i++) {
            if($i==0) continue;
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$i]}</td>";
          }
          echo "</tr>";
        }

        echo "</tr>";
        echo "</table>";

       ?>

      </div>
  </body>
</html>