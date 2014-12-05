<script type="text/javascript" src="netteForms.js"></script>

<?php

include "connect.php";

if(isset($_GET["user"])) {
	echo "<div id=logout_btn><a href='?page=index.php'>Odhlásit se</a></div>";
	switch($_GET["user"]) {
		case "aranzer":
			$tabulka = "Skladba";
			$nadpisy_sloupcu = array('ID skladby', 'Název','Délka','ID autora');
			$nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'ID_autora');
			$pk = "ID_skladby";
			$nadpis_vysledku = "Seznam skladeb";
			echo '<div id="menu"><ul>';
			echo "<li><a href='add_song'>Pøidat skladbu</a></li>";
			echo "</ul><div>";
			break;
		// case "personalista":
		// 	$tabulka = "Hudebnik";
		// 	$nadpisy_sloupcu = array('Rodné èíslo', 'Jméno', 'Pøíjmení');
		// 	$nazvy_sloupcu = array('rodne_cislo', 'jmeno', 'prijmeni');
		// 	$pk = "rodne_cislo";
		// 	$nadpis_vysledku = "Seznam hudebníkù";
		// 	echo '<div id="menu"><ul>';
		// 	echo "<ul><li><a href='addform.php'>Pøidat zamìstnance</a></li>";
		// 	echo "</ul><div>";
		// 	break;
		case "nastrojar":
			$tabulka = "Nastroj";
			$nadpisy_sloupcu = array('Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmì›ny', 'Vymìnìno', 'Výrobní èíslo', "Typ");
			$nazvy_sloupcu = array('datum_vyroby', 'vyrobce', 'dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
			$pk = "vyrobni_cislo";
			$nadpis_vysledku = "Seznam nástrojù";
			echo '<div id="menu"><ul>';
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
			echo '<div id="menu"><ul>';
			echo "<li><a href='first_concert'>Zobraz nejbli¾¹í koncert</a></li>";
			echo "</ul><div>";
			break;
		case "manazer":
			$tabulka = "Koncert";
			$nadpisy_sloupcu = array('ID koncertu', 'Datum a èas', 'Mìsto', 'Adresa');
			$nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
			$pk = "ID_koncertu";
			$nadpis_vysledku = "Seznam koncertù";
			echo '<div id="menu"><ul>';
			echo "<li><a href='naplanuj_koncert'>Naplánuj koncert</a></li>";
			echo "</ul><div>";
			break;
	}

}
?>

<!-- vvvvvvvvvvvvvvvvvvvvvvvv HTML vvvvvvvvvvvvvvvvvvvvvvvv -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styl.css">
		<meta charset="iso-8859-2">
		<script src="js/libs/jquery-2.1.1.js"></script>
		<script src="js/filter.js"></script>
		<title>Filharmonie Liptákov</title>
	</head>

	<body>
		<?php
			//jestlize je vybran uzivatel
			if(isset($_GET["page"])) {
	      include $_GET['page'];
			}
			//neni vybran zadny uzivatel; obrazovka pro vyber role uzivatele
			//prihlasit se jako: manazer, hudebnik, personalista, nastrojar, aranzer
			else {
				echo "Vítejte v informaèním systému Filharmonie Liptákov!<br>";
	    	echo '<div id="menu"><ul>
	      <li><a href="?page=manazer.php">Mana¾er</a></li>
	      <li><a href="?page=personalista.php">Personalista</a></li>
	      <li><a href="?page=hudebnik.php">Hudebník</a></li>
	      <li><a href="?page=aranzer.php">Aran¾ér</a></li>
	      <li><a href="?page=nastrojar.php">Nástrojáø</a></li></ul></div>';
			}
	?>

	</body>
</html>
