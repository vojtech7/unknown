<?php

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
  function print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu, $ignore=null, $buttons=null,$PK=null, $role=null, $page=null)
  {
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
      echo "<table  class=\"data\" class=\"\">";
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
          else
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>";
            $j++;
        }
         
        if ($buttons != null) {
          if (in_array("delete", $buttons) and $PK != null) {
              //predam si PK do url parametru delete
              echo "<td id=delete_btn><a href='?page={$page}&delete={$row[$PK]}'>Odstranit</a></td>";
             
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
      return;
  }
?>