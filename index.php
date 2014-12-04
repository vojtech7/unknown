<script type="text/javascript" src="netteForms.js"></script>

<?php


require 'Nette/loader.php';
use Nette\Forms\Form;

	include "connect.php";

if(isset($_GET["user"])) {
	// $buttons->addButton('logout', 'Odhlásit');
	switch($_GET["user"]) {
		case "aranzer":
			$tabulka = "Skladba";
			$nadpisy_sloupcu = array('ID skladby', 'Název','Délka','ID autora');
			$nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'ID_autora');
			$pk = "ID_skladby";
			$nadpis_vysledku = "Seznam skladeb";
			echo '<div id="tlacitka"><ul>';
			echo "<li><a href='add_song'>Pøidat skladbu</a></li>";
			echo "</ul><div>";
			break;
		case "personalista":
			$tabulka = "Hudebnik";
			$nadpisy_sloupcu = array('Rodné èíslo', 'Jméno', 'Pøíjmení');
			$nazvy_sloupcu = array('rodne_cislo', 'jmeno', 'prijmeni');
			$pk = "rodne_cislo";
			$nadpis_vysledku = "Seznam hudebniku";
			echo '<div id="tlacitka"><ul>';
			echo "<ul><li><a href='add_human'>Pøidat zamìstnance</a></li>";
			echo "</ul><div>";
			break;
		case "nastrojar":
			$tabulka = "Nastroj";
			$nadpisy_sloupcu = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmì›ny', 'Vymìnìno', 'Výrobní èíslo', "Typ");
			$nazvy_sloupcu = array('datum_vyroby', 'vyrobce', 'dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
			$pk = "vyrobni_cislo";
			$nadpis_vysledku = "Seznam nástrojù";
			echo '<div id="tlacitka"><ul>';
			echo "<li><a href='add_instrument'>Pøidat nástroj</a></li>";
			// $buttons->addTextArea('vymena', 'Vymìnit èásti');
			echo "<li><a href='vymena_casti'>Zadat výmìnu èástí</a></li>";
			echo "<li><a href='revize'>Zaznamenat revizi</a></li>";
			echo "</ul><div>";
			break;
		case "hudebnik":
			$tabulka = "Koncert";
			$nadpisy_sloupcu = array('ID koncertu', 'Datum a èas', 'Mìsto', 'Adresa');
			$nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
			$pk = "ID_koncertu";
			$nadpis_vysledku = "Seznam koncertù";
			echo '<div id="tlacitka"><ul>';
			echo "<li><a href='first_concert'>Zobraz nejbli¾¹í koncert</a></li>";
			echo "</ul><div>";
			break;
		case "manazer":
			$tabulka = "Koncert";
			$nadpisy_sloupcu = array('ID koncertu', 'Datum a èas', 'Mìsto', 'Adresa');
			$nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
			$pk = "ID_koncertu";
			$nadpis_vysledku = "Seznam koncertù";
			echo '<div id="tlacitka"><ul>';
			echo "<li><a href='naplanuj_koncert'>Naplánuj koncert</a></li>";
			echo "</ul><div>";
			break;
	}


	$select = new Form;

	$select->addText('value_1');
	$select->addText('value_2');
	$select->addText('value_3');
	$select->addText('value_4');
	$select->addText('value_5');
	$select->addText('value_6');
	$select->addText('value_7');
	// $select->addSubmit('find_song', 'Vyhledat skladbu');

	if ($select->isSuccess()) {
		echo 'Form was submitted and successfully validated';
		dump($select->getValues());
		exit;
	}
}
?>



<!-- vvvvvvvvvvvvvvvvvvvvvvvv HTML vvvvvvvvvvvvvvvvvvvvvvvv -->


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styl.css">
<meta charset="iso-8859-2">
<script src="js/filter.js"></script>
<title>Filharmonie Liptákov</title>
</head>

<body>
	<?php
	//jestlize je vybran uzivatel
		if(isset($_GET["user"])) {
			echo '<div id="logout" class="buttons">';
			// $buttons['logout']->control;	
			echo "</div>";
			echo '<table id="hledani" class="pattern">';
			echo '<span class="nadpis" id="nadpis_vyhledavani">';

			switch ($_GET["user"]) {
				case "aranzer" :
					echo "Filtry pro vyhledávání skladeb";
					break;
				case "personalista" :
					echo "Filtry pro vyhledávání zamìstnancù";
					break;
				case "nastrojar" :
					echo "Filtry pro vyhledávání nástrojù";
					break;
				default:
					# code...
					break;
			}

			echo "</span>";
			echo "<tr>";

			$count=0;
			foreach ($nadpisy_sloupcu as $value) {
				echo "<td>". $value ."</td>";
				$count++;
			}

			echo "</tr>";
			echo "<tr>";

			for ($i=1; $i <= $count ; $i++) { 
				$vypis="value_".$i;
				echo "<td>".$select[$vypis]->control."</td>";
			}

			echo "</tr>";
			echo "</table>";

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

				/*tahání dat z databáze*/
				$sql = "select * from ".$tabulka;
				$vysledek = mysql_query($sql);

        while($row = mysql_fetch_array($r)){
				  echo "<tr>";
				  // for ($i=0; $i < ; $i++) { 
      //     	echo "<td class='filter_id_lek'>{$row['id_lek']}</td>";
				  // }

					echo "<td id=edit_btn><a href='?edit=$pk'>Upravit</a></td>";
					echo "<td id=delete_btn><a href='javascript:alert(\"Delete\");'>Odstranit</a></td>";
				  echo "</tr>";
				}

				echo "</tr>";
				echo "</table>";
				echo '<div id="tlacitka">';

			}

			//neni vybran zadny uzivatel; obrazovka pro vyber role uzivatele
			//prihlasit se jako: manazer, hudebnik, personalista, nastrojar, aranzer
			else {
				echo "Vítejte v informaèním systému Filharmonie Liptákov!<br>";
      	echo '<div id="tlacitka"><ul>
        <li><a href="?user=manazer">Mana¾er</a></li>
        <li><a href="?user=personalista">Personalista</a></li>
        <li><a href="?user=hudebnik">Hudebník</a></li>
        <li><a href="?user=aranzer">Aran¾ér</a></li>
        <li><a href="?user=nastrojar">Nástrojáø</a></li></ul></div>';
			}

		 ?>

		</div>
	
</body>
</html>
