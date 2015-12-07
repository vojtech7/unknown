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
    <title>Filharmonie Liptákov</title>
  </head>
  <body>
    <?php
      include "connect.php";
      include 'functions.php';
      
      if(isset($_POST['id_aut'])) {
        $id_aut = $_POST['id_aut'];
        header("Location:?id_aut=$id_aut"); //poslu id koncertu do get parametru
      }
      //id koncertu poslano pres get, z odkazu ve vypisu koncertu
      elseif (isset($_GET['id_aut'])) {
        $id_aut = $_GET['id_aut'];
      }
      else {
        echo "Neni zadan konkretni autor.";
        exit();
      }

      $sql_autor = "SELECT * FROM Autor WHERE ID_autora='$id_aut'";
      $autor_vysledek = user_db_query($sql_autor);
      $autor = mysql_fetch_array($autor_vysledek);
      $jmeno=  $autor['jmeno'];
      $zac_tv =  $autor['zacatek_tvorby'];
      $kon_tv =  $autor['konec_tvorby'];
      $styl = $autor['styl'];

      echo "<h1>Detail autora $jmeno</h1>";

      echo "<br><ul>
                  <li>Začátek tvorby: $zac_tv</li>
                  <li>Konec tvorby: $kon_tv</li>
                  <li>Styl: $styl</li>
                </ul> ";
      
                //tabulka skladeb
      $sql = "SELECT nazev, styl, delka
                FROM Skladba NATURAL JOIN Autor
                WHERE ID_autora = $id_aut";
      
      $title = "Seznam skladeb autora";
  
      $nadpisy_sloupcu = array('Název', 'Styl', 'Délka');
      $nazvy_sloupcu = array('nazev', 'styl', 'delka');
      print_table($sql, $title, $nadpisy_sloupcu, $nazvy_sloupcu);

      echo "<a href='aranzer.php'>Zpet na vypis autoru</a>";
    ?>
  </body>
</html>
