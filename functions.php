<?php
  /**********************************************
        TABULKA PRO VÝSLEDKY
  ***********************************************/
  /*
    Parametry funkce:
      povinné:
        sql: dotaz do databáze
        title: Nadpis tabulky
        nadpisy_sloupců: názvy sloupců zobrazované v hlavičce
        nazvy_sloupcu: v těle tabulky
      nepovinné:
        ignore: jména sloupců, které se nevypisujou
        buttons: tlačítka, která mají být v tabulce (pole stringů)
        PK: primární klíč (podle kterého se odtraňuje atd.)
        role: role
  */
  function print_table($tabulka, $role=null, $page=null)
  {
      $nadpisy_sloupcu = $tabulka["nadpisy_sloupcu"];
      $nazvy_sloupcu = $tabulka["nazvy_sloupcu"];
      $tabulka_upravy = $tabulka["tabulka_upravy"];
      $ignore = $tabulka["ignore"];
      $sql = $tabulka["sql"];
      $title = $tabulka["title"];
      $buttons = $tabulka["buttons"];
      $PK = $tabulka["PK"];
      
      $table = mysql_query($sql);
      $columns_count = count($nadpisy_sloupcu);
      
      /*
      $alter = hodnoty v¹ech sloupcù tabulky oddìlené vlnovkou ~~
      pøedává se do formuláøe pro úpravu polozky
        */
      $alter="";

      //pokud vykresluju tabulky pro přehled (a mazaní, tak tam potřebuju kvůli vzhledu i id="prehled")
      if ($buttons!=null) {
        $data = "id=\"prehled\"";
      }
      else
        $data = "";
      
      echo "<h3>$title</h3>";
      echo "<table  $data class=\"data\" class=\"\">";
        //výpis hlavičky tabulky
      for ($i=0; $i <$columns_count ; $i++) { 
        echo "<td class='filter_{$nadpisy_sloupcu[$i]}'>$nadpisy_sloupcu[$i]</td>";

      }

        //výpis těla
      while ($row = mysql_fetch_array($table, MYSQL_ASSOC)) {
        echo "<tr>";
        for ($i=0, $j=0; $i <count($nazvy_sloupcu); $i++) {
          $alter = $alter.$row[$nazvy_sloupcu[$i]]."~~";
          if (is_array($ignore) and in_array($nazvy_sloupcu[$i], $ignore)) 
            continue;
          elseif ($nazvy_sloupcu[$i] == "nazev") {
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='skladba.php?id_kon=0'>{$row[$nazvy_sloupcu[$i]]}</a></td>";
          }
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>";
            $j++;
        }
         
        if ($buttons != null) {
          if (in_array("delete", $buttons) and $PK != null) {
              //predam si PK do url parametru delete
              echo "<td id=delete_btn><a href='?page={$page}&delete={$row[$PK]}&tabulka={$tabulka_upravy}'>Odstranit</a></td>";
             
           } 
          if (in_array("edit", $buttons)) {
            //dám $alter do uvozovek
            $alter="\"".$alter."\"";
            echo "<td class=alter_btn><button onclick='P_alter_form_show($alter, \"$role\", \"$title\")'>Upravit</button></td>";
            $alter="";
          }
        }

      }
      echo "</tr>";
      
      echo "</table>";
  }



/*********************************
    Tabulka Pro vyhledávání
*********************************/
    function print_search_table($nadpisy_sloupcu, $nazvy_sloupcu, $class=null, $seznam_jmen=null, $ignore=null)
    {
          //tabulka se vstupy pro hledani
      echo '<table id="hledani" class="pattern" class="$class">
            <span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání</span>
            <tr>';
        foreach ($nadpisy_sloupcu as $value) {
          echo "<td>". $value ."</td>";
        }
        echo "</tr>";
        echo "<tr>";
        foreach ($nazvy_sloupcu as $value) {
          if ($ignore != null and in_array($value, $ignore)) 
            continue;
          
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
      
    }
?>