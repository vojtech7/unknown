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
        page
  */
  function print_table($sql, $title=null, $nadpisy_sloupcu=null, $nazvy_sloupcu=null, $ignore=null, $buttons=null, $PK=null, $role=null, $page=null)
  {
    if (is_array($sql)) {
      //pokud je zadane pole parametru, tak se na posledni dva namapuji 2. a 3. parametr
      $role = $title;
      $page = $nadpisy_sloupcu;
      //dal se to mapuje aspon trochu inteligentne
      $title = $sql["title"];
      $nadpisy_sloupcu = $sql["nadpisy_sloupcu"];
      $nazvy_sloupcu = $sql["nazvy_sloupcu"];
      $ignore = $sql["ignore"];
      $tabulka_uprav = $sql["tabulka_uprav"];
      $buttons = $sql["buttons"];
      $PK = $sql["PK"];
      $sql = $sql["sql"];
    }
      
      $table = user_db_query($sql);
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

      echo "<table $data class=\"data\" class=\"\">";
        //výpis hlavičky tabulky
      for ($i=0; $i <$columns_count ; $i++) { 
        echo "<td class='filter_{$nadpisy_sloupcu[$i]}'>$nadpisy_sloupcu[$i]</td>";

      }

        //výpis těla
      while ($row = mysql_fetch_array($table, MYSQL_ASSOC)) {
        echo "<tr>";
        for ($i=0; $i <count($nazvy_sloupcu); $i++) {
          $alter = $alter.$row[$nazvy_sloupcu[$i]]."~~";
                      //sloupce, které se nevykreslují
          if (is_array($ignore) and in_array($nazvy_sloupcu[$i], $ignore)) 
            continue;
                      //název skladby -> přidává se odkaz na skladbu
          elseif ($nazvy_sloupcu[$i] == "nazev") {
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='skladba.php?id_skl={$row["ID_skladby"]}'>{$row[$nazvy_sloupcu[$i]]}</a></td>";
            continue;
          }
                      //nahrazení prázdného data ---
          elseif (strpos($nazvy_sloupcu[$i], "dat") !== false) {
            if ($row[$nazvy_sloupcu[$i]]==null) {
              $mydate="---";
              $alter=$alter."~~";
            }
            else{
              $date = date_create($row[$nazvy_sloupcu[$i]]);
              $mydate = date_format($date, "d.m.Y");
              $alter = $alter.$mydate."~~";
            }
            echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$mydate}</td>";
            continue;
          }

                    //defaultní vykreslení buňky
          echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>";
        }
         
        if ($buttons != null) {
          if (in_array("delete", $buttons) and $PK != null) {
              //predam si PK do url parametru delete
              echo "<td id=delete_btn><a href='?delete={$row[$PK]}&tabulka={$tabulka_uprav}' onclick='return confirm(\"Opravdu odstranit?\");'>Odstranit</a></td>";
             
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

/*********************************
      Wrapper nad mysql_query()
*********************************/
/*
  Parametry funkce:
    povinné:
      sql: dotaz do databáze
*/
function user_db_query($sql)
{
  require 'connect.php';
  $result = mysql_query($sql) or trigger_error(mysql_error());
  if ($result == false) {
    exit();
  }
  return $result;
}

?>