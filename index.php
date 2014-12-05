<script type="text/javascript" src="netteForms.js"></script>

<?php

include "connect.php";

if(isset($_GET["user"])) {
	echo "<div id=logout_btn><a href='?page=index.php'>Odhl�sit se</a></div>";
	switch($_GET["user"]) {
		case "aranzer":
			$tabulka = "Skladba";
			$nadpisy_sloupcu = array('ID skladby', 'N�zev','D�lka','ID autora');
			$nazvy_sloupcu = array('ID_skladby', 'nazev', 'delka', 'ID_autora');
			$pk = "ID_skladby";
			$nadpis_vysledku = "Seznam skladeb";
			echo '<div id="menu"><ul>';
			echo "<li><a href='add_song'>P�idat skladbu</a></li>";
			echo "</ul><div>";
			break;
		// case "personalista":
		// 	$tabulka = "Hudebnik";
		// 	$nadpisy_sloupcu = array('Rodn� ��slo', 'Jm�no', 'P��jmen�');
		// 	$nazvy_sloupcu = array('rodne_cislo', 'jmeno', 'prijmeni');
		// 	$pk = "rodne_cislo";
		// 	$nadpis_vysledku = "Seznam hudebn�k�";
		// 	echo '<div id="menu"><ul>';
		// 	echo "<ul><li><a href='addform.php'>P�idat zam�stnance</a></li>";
		// 	echo "</ul><div>";
		// 	break;
		case "nastrojar":
			$tabulka = "Nastroj";
			$nadpisy_sloupcu = array('Datum v�roby', 'V�robce', 'Datum posledn� revize', 'Datum posledn� v�m�ny', 'Vym�n�no', 'V�robn� ��slo', "Typ");
			$nazvy_sloupcu = array('datum_vyroby', 'vyrobce', 'dat_posl_revize', 'dat_posl_vymeny', 'vymeneno', 'vyrobni_cislo', 'ttype');
			$pk = "vyrobni_cislo";
			$nadpis_vysledku = "Seznam n�stroj�";
			echo '<div id="menu"><ul>';
			echo "<li><a href='add_instrument'>P�idat n�stroj</a></li>";
			// $buttons->addTextArea('vymena', 'Vym�nit ��sti');
			echo "<li><a href='vymena_casti'>Zadat v�m�nu ��st�</a></li>";
			echo "<li><a href='revize'>Zaznamenat revizi</a></li>";
			echo "</ul><div>";
			break;
		case "hudebnik":
			$tabulka = "Koncert";
			$nadpisy_sloupcu = array('ID koncertu', 'Datum a �as', 'M�sto', 'Adresa');
			$nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
			$pk = "ID_koncertu";
			$nadpis_vysledku = "Seznam koncert�";
			echo '<div id="menu"><ul>';
			echo "<li><a href='first_concert'>Zobraz nejbli��� koncert</a></li>";
			echo "</ul><div>";
			break;
		case "manazer":
			$tabulka = "Koncert";
			$nadpisy_sloupcu = array('ID koncertu', 'Datum a �as', 'M�sto', 'Adresa');
			$nazvy_sloupcu = array('ID_koncertu', 'datum_a_cas', 'mesto', 'adresa');
			$pk = "ID_koncertu";
			$nadpis_vysledku = "Seznam koncert�";
			echo '<div id="menu"><ul>';
			echo "<li><a href='naplanuj_koncert'>Napl�nuj koncert</a></li>";
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
		<title>Filharmonie Lipt�kov</title>
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
				echo "V�tejte v informa�n�m syst�mu Filharmonie Lipt�kov!<br>";
	    	echo '<div id="menu"><ul>
	      <li><a href="?page=manazer.php">Mana�er</a></li>
	      <li><a href="?page=personalista.php">Personalista</a></li>
	      <li><a href="?page=hudebnik.php">Hudebn�k</a></li>
	      <li><a href="?page=aranzer.php">Aran��r</a></li>
	      <li><a href="?page=nastrojar.php">N�stroj��</a></li></ul></div>';
			}
	?>

	</body>
</html>
