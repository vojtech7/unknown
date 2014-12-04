<script type="text/javascript" src="netteForms.js"></script>

<?php


require 'Nette/loader.php';
use Nette\Forms\Form;

	include "connect.php";

if(isset($_GET["user"])) {
	// $buttons->addButton('logout', 'Odhl�sit');
	if ($_GET["user"] == "aranzer") {
		echo '<div id="tlacitka"><ul>';
		echo "<li><a href='add_song'>P�idat skladbu</a></li>";
		echo "<li><a href='edit_song'>Upravit skladbu</a></li>";
		echo "<li><a href='delete_song'>Odstranit skladbu</a></li>";
		echo "</ul><div>";
	}
	if ($_GET["user"] == "personalista") {
		echo '<div id="tlacitka"><ul>';
		echo "<ul><li><a href='add_human'>P�idat zam�stnance</a></li>";
		echo "<li><a href='edit_human'>Upravit zam�stnance</a></li>";
		echo "<li><a href='delete_human'>Odstranit zam�stnance</a></li>";
		echo "</ul><div>";
	}
	if ($_GET["user"] == "nastrojar") {
		echo '<div id="tlacitka"><ul>';
		echo "<li><a href='add_instrument'>P�idat n�stroj</a></li>";
		// $buttons->addTextArea('vymena', 'Vym�nit ��sti');
		echo "<li><a href='vymena_casti'>Zadat v�m�nu ��st�</a></li>";
		echo "<li><a href='revize'>Zaznamenat revizi</a></li>";
		echo "<li><a href='delete_instrument'>Odstranit n�stroj</a></li>";
		echo "</ul><div>";
	}
	if ($_GET["user"] == "hudebnik") {
		echo '<div id="tlacitka"><ul>';
		echo "<li><a href='first_concert'>Zobraz nejbli��� koncert</a></li>";
		echo "</ul><div>";
	}
	if ($_GET["user"] == "manazer") {
		echo '<div id="tlacitka"><ul>';
		echo "<li><a href='naplanuj_koncert'>Napl�nuj koncert</a></li>";
		echo "<li><a href='stornuj_koncert'>Stornuj koncert</a></li>";
		echo "</ul><div>";
	}

	$moznosti_aranzer = array('ID skladby', 'N�zev','D�lka','ID autora' );
	$moznosti_personalista = array('Rodn� ��slo', 'Jm�no', 'P��jmen�');
	$moznosti_nastrojar = array( 'Datum v�roby', 'V�robce', 'Datum posledn� revize', 'Datum posledn� v�m�ny', 'Vym�n�no', 'V�robn� ��slo', "Typ");
	$moznosti_manazer = array('ID koncertu', 'Datum a �as', 'M�sto', 'Adresa');
	$moznosti_hudebnik = array('ID koncertu', 'Datum a �as', 'M�sto', 'Adresa');


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
<? header("Content-Type: text/html; charset=iso-8859-2");?>
<script src="js/filter.js"></script>
<title>Filharmonie Lipt�kov</title>
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
					echo "Filtry pro vyhled�v�n� skladeb";
					break;
				case "personalista" :
					echo "Filtry pro vyhled�v�n� zam�stnanc�";
					break;
				case "nastrojar" :
					echo "Filtry pro vyhled�v�n� n�stroj�";
					break;
				default:
					# code...
					break;
			}

			echo "</span>";
			echo "<tr>";

				$vyber = "moznosti_".$_GET["user"];
				$count=0;
				
				foreach ($$vyber as $value) {
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
			echo "<div class='buttons' id='vyhledat'>";

			if ($_GET["user"] == "aranzer") {
	     	// echo $select['find_song']->control;
			}
			elseif ($_GET["user"] == "personalista") {
				// echo $buttons['find_human']->control;
			}
			elseif ($_GET["user"] == "nastrojar") {
				// echo $buttons['find_instrument']->control;
			}

			echo "</div>";
			echo '<table id="prehled" class="data">';
			echo '<span class="nadpis" id="nadpis_vysledku">';

			switch ($_GET["user"]) {
				case "aranzer" :
					echo "Seznam  skladeb";
					break;
				case "personalista" :
					echo "Seznam  zam�stnanc�";
					break;
				case "nastrojar" :
					echo "Seznam  n�stroj�";
					break;
				default:
					# code...
					break;
			}

			echo "</span>";
			echo "<tr>";
			echo '<td class="hlavicka" id="check">V�b�r</td>';

			$vyber = "moznosti_".$_GET["user"];
			$count=0;
			
			foreach ($$vyber as $value) {
				echo "<td class=\"hlavicka\">". $value ."</td>";
				$count++;
			}
				
			echo "</tr>";
			echo "<tr>";

				switch ($_GET["user"]) {
					case 'aranzer':
						$tabulka = "Skladba";
						break;
					case 'personalista':
						$tabulka = "Hudebnik";
						break;
					case 'nastrojar':
						$tabulka = "Nastroj";
						break;
					case 'hudebnik':
						$tabulka = "Koncert";
						break;
					case 'manazer':
						$tabulka = "Koncert";
						break;

					default:
						# code...
						break;
				}
			
				/*tah�n� dat z datab�ze*/
				$sql = "select * from ".$tabulka;
				$vysledek = mysql_query($sql);

				for ($i=0; $i < mysql_num_rows($vysledek); $i++) { 
				  $row = mysql_fetch_row($vysledek);
				  echo "<tr>";
				  echo "<td><input type=\"checkbox\"></td>";
				  for ($j=0; $j < mysql_num_fields($vysledek); $j++) { 
				    echo "<td>".$row[$j];"</td>";
				  }
				  echo "</tr>";
				}

			echo "</tr>";
			echo "</table>";
			echo '<div id="tlacitka">';
		

			// switch ($_GET["user"]) {
			// 	case 'aranzer':	
			// 		echo "<div class=\"buttons\" id=\"add\">".$buttons['add_song']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"alter\">".$buttons['alter_song']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"delete\">".$buttons['delete_song']->control."</div>";
			// 		break;
			// 	case 'personalista':
			// 		echo "<div class=\"buttons\" id=\"add\">".$buttons['add_human']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"alter\">".$buttons['alter_human']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"delete\">".$buttons['delete_human']->control."</div>";
			// 		break;
			// 	case 'nastrojar':
			// 		echo "<div class=\"buttons\" id=\"add\">".$buttons['add_instrument']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"blok\">".$buttons['vymena']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"alter_intrument\">".$buttons['vymena_casti']->control."</div>";
			// 		echo "<div class=\"buttons\" id=\"delete_instrument\">".$buttons['delete_instrument']->control."</div>";
			// 		break;
			// 	case 'hudebnik':
					
			// 		break;
			// 	default:
					
			// 		break;
			// 	}
			}

			//neni vybran zadny uzivatel; obrazovka pro vyber role uzivatele
			//prihlasit se jako: manazer, hudebnik, personalista, nastrojar, aranzer
			else {
				echo "V�tejte v informa�n�m syst�mu Filharmonie Lipt�kov!<br>";
      	echo '<div id="tlacitka"><ul>
        <li><a href="?user=manazer">Mana�er</a></li>
        <li><a href="?user=personalista">Personalista</a></li>
        <li><a href="?user=hudebnik">Hudebn�k</a></li>
        <li><a href="?user=aranzer">Aran��r</a></li>
        <li><a href="?user=nastrojar">N�stroj��</a></li></ul></div>';
			}

		 ?>

		</div>
	
</body>
</html>
