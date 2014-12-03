<script type="text/javascript" src="netteForms.js"></script>

<?php


require 'Nette/loader.php';
use Nette\Forms\Form;

	include "connect.php";

if(isset($_GET["user"])) {
	$buttons = new Form;
	$buttons->addButton('logout', 'OdhlÃ¡sit');
	if ($_GET["user"] == "aranzer") {
		$buttons->addButton('add_song', 'Pøidat skladbu');
		
		$buttons->addButton('alter_song', 'Upravit skladbu');
		$buttons->addButton('delete_song', 'Odstranit skladbu');
	}
	if ($_GET["user"] == "personalista") {
		$buttons->addButton('find_human', 'Vyhledat zamìstnance');
		$buttons->addButton('add_human', 'Pøidat zamìstnance');
		$buttons->addButton('alter_human', 'Upravit zamìstnance');
		$buttons->addButton('delete_human', 'Odstranit zamìstnance');
	}
	if ($_GET["user"] == "nastrojar") {
		$buttons->addButton('find_instrument', 'Vyhledat nástroj');
		$buttons->addButton('add_instrument', 'Pøidat nástroj');
		$buttons->addTextArea('vymena', 'Vymìnit èásti');
		$buttons->addButton('vymena_casti', 'Zadej výmìnu èástí');
		$buttons->addButton('revize', 'Zaznamenat revizi');
		$buttons->addButton('delete_instrument', 'Odstranit nástroj');
	}
	if ($_GET["user"] == "hudebnik") {
		$buttons->addButton('find_concert', 'Vyhledat koncert');
		$buttons->addButton('first_concert', 'Zobraz nejbli¾¹í koncert');
	}

	$moznosti_aranzer = array('ID skladby', 'Název','Délka','ID autora' );
	$moznosti_personalista = array('Rodné èíslo', 'Jméno', 'Pøíjmení');
	$moznosti_nastrojar = array( 'Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmì›ny', 'Vymìnìno', 'Výrobní èíslo', "Typ");


	$select = new Form;

	$select->addText('value_1');
	$select->addText('value_2');
	$select->addText('value_3');
	$select->addText('value_4');
	$select->addText('value_5');
	$select->addText('value_6');
	$select->addText('value_7');
	$select->addSubmit('find_song', 'Vyhledat skladbu');

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
<title>Filharmonie LiptÃ¡kov</title>
</head>

<body>
	<?php
	//jestlize je vybran uzivatel
		if(isset($_GET["user"])) {
			echo '<div id="logout" class="buttons">';
			$buttons['logout']->control;	
			echo "</div>";
			echo '<table id="hledani">';
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
	     	echo $select['find_song']->control;
			}
			elseif ($_GET["user"] == "personalista") {
				echo $buttons['find_human']->control;
			}
			elseif ($_GET["user"] == "nastrojar") {
				echo $buttons['find_instrument']->control;
			}

			echo "</div>";
			echo '<table id="prehled">';
			echo '<span class="nadpis" id="nadpis_vysledku">';

			switch ($_GET["user"]) {
				case "aranzer" :
					echo "Seznam  skladeb";
					break;
				case "personalista" :
					echo "Seznam  zamìstnancù";
					break;
				case "nastrojar" :
					echo "Seznam  nástrojù";
					break;
				default:
					# code...
					break;
			}

			echo "</span>";
			echo "<tr>";
			echo '<td class="hlavicka" id="check">Výbì›r</td>';

			if (isset($_GET["user"])) {
				$vyber = "moznosti_".$_GET["user"];
				$count=0;
				
				foreach ($$vyber as $value) {
					echo "<td class=\"hlavicka\">". $value ."</td>";
					$count++;
				}
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
			
				/*tahÃ¡nÃ­ dat z databÃ¡ze*/
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
		

			switch ($_GET["user"]) {
				case 'aranzer':	
					echo "<div class=\"buttons\" id=\"add\">".$buttons['add_song']->control."</div>";
					echo "<div class=\"buttons\" id=\"alter\">".$buttons['alter_song']->control."</div>";
					echo "<div class=\"buttons\" id=\"delete\">".$buttons['delete_song']->control."</div>";
					break;
				case 'personalista':
					echo "<div class=\"buttons\" id=\"add\">".$buttons['add_human']->control."</div>";
					echo "<div class=\"buttons\" id=\"alter\">".$buttons['alter_human']->control."</div>";
					echo "<div class=\"buttons\" id=\"delete\">".$buttons['delete_human']->control."</div>";
					break;
				case 'nastrojar':
					echo "<div class=\"buttons\" id=\"add\">".$buttons['add_instrument']->control."</div>";
					echo "<div class=\"buttons\" id=\"blok\">".$buttons['vymena']->control."</div>";
					echo "<div class=\"buttons\" id=\"alter_intrument\">".$buttons['vymena_casti']->control."</div>";
					echo "<div class=\"buttons\" id=\"delete_instrument\">".$buttons['delete_instrument']->control."</div>";
					break;
				case 'hudebnik':
					
					break;
				default:
					
					break;
				}
			}

			//neni vybran zadny uzivatel; obrazovka pro vyber role uzivatele
			//prihlasit se jako: manazer, hudebnik, personalista, nastrojar, aranzer
			else {
				echo "Vítejte v informaèním systému Filharmonie Liptákov!<br>";
      	echo '<ul> <div style="font-size:medium; font-weight:bold;">Objednávky</div>        
        <li><a href="?user=manazer">Mana¾er</a></li>  
        <li><a href="?user=personalista">Personalista</a></li>          
        <li><a href="?user=hudebnik">Hudebník</a></li>
        <li><a href="?user=aranzer">Aran¾ér</a></li>
        <li><a href="?user=nastrojar">Nástrojáø</a></li></ul>';
			}

		 ?>

		</div>
	
</body>
</html>
