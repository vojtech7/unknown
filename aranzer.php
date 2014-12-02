<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/styl.css">
<title>Filharmonie Liptákov</title>



<?php
require 'Nette/loader.php';
use Nette\Forms\Form;
?>

</head>
<body>
		<?php
			$buttons = new Form;
			$buttons->addButton('logout', 'Odhlásit');
			if ($_GET["user"] == "aranzer") {
				$buttons->addButton('find_song', 'Vyhledat skladbu');
				$buttons->addButton('add_song', 'Přidat skladbu');
				$buttons->addButton('alter_song', 'Upravit skladbu');
				$buttons->addButton('delete_song', 'Odstranit skladbu');
			}
			if ($_GET["user"] == "personalista") {
				$buttons->addButton('find_human', 'Vyhledat zaměstnance');
				$buttons->addButton('add_human', 'Přidat zaměstnance');
				$buttons->addButton('alter_human', 'Upravit zaměstnance');
				$buttons->addButton('delete_human', 'Odstranit zaměstnance');
			}
			if ($_GET["user"] == "nastrojar") {
				$buttons->addButton('find_instrument', 'Vyhledat zaměstnance');
				$buttons->addButton('add_instrument', 'Přidat zaměstnance');
				$buttons->addButton('alter_instrument', 'Upravit zaměstnance');
				$buttons->addButton('delete_instrument', 'Odstranit zaměstnance');
			}
		?>
		<div id="logout" class="buttons"><?php echo $buttons['logout']->control ?></div>

		<?php
			$moznosti_aranzer = array('ID skladby', 'Název','Délka','ID autora' );
			$moznosti_personalista = array('Rodné číslo', 'Jméno', 'Příjmení');
			$moznosti_nastrojar = array('Výrobní číslo', 'Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výměny', 'Vyměněno');
			

			$select = new Form;
		
			$select->addText('value_1');
			$select->addText('value_2');
			$select->addText('value_3');
			$select->addText('value_4');
			$select->addText('value_5');
			$select->addText('value_6');
			$select->addText('value_7');

		?>

		<table id="hledani">
			<span class="nadpis" id="nadpis_vyhledavani">
			<?php
				switch ($_GET["user"]) {
					case "aranzer" :
						echo "Filtry pro vyhledávání skladeb";
						break;
					case "personalista" :
						echo "Filtry pro vyhledávání zaměstnanců";
						break;
					case "nastrojar" :
						echo "Filtry pro vyhledávání nástrojů";
						break;
					default:
						# code...
						break;
				}

			?>
			</span>
			<tr>
				<?php
				$vyber = "moznosti_".$_GET["user"];
				$count=0;
				
				foreach ($$vyber as $value) {
					echo "<td>". $value ."</td>";
					$count++;
				}

				?>
			</tr>
			<tr>
				<?php
					for ($i=1; $i <= $count ; $i++) { 
						$vypis="value_".$i;
						echo "<td>".$select[$vypis]->control."</td>";
					}
				?>

			</tr>
		</table>
		<div class="buttons" id="vyhledat"><?php if ($_GET["user"] == "aranzer") {
											     	echo $buttons['find_song']->control;
												}
												elseif ($_GET["user"] == "personalista") {
													echo $buttons['find_human']->control;
												}
												elseif ($_GET["user"] == "nastrojar") {
													echo $buttons['find_instrument']->control;
												}
											 ?></div>
	
		<table id="prehled">
			<span class="nadpis" id="nadpis_vysledku">
			<?php
				switch ($_GET["user"]) {
					case "aranzer" :
						echo "Seznam  skladeb";
						break;
					case "personalista" :
						echo "Seznam  zaměstnanců";
						break;
					case "nastrojar" :
						echo "Seznam  nástrojů";
						break;
					default:
						# code...
						break;
				}

			?>
			</span>
			<tr>
				<td class="hlavicka" id="check">Výběr</td>
				<?php
				$vyber = "moznosti_".$_GET["user"];
				$count=0;
				
				foreach ($$vyber as $value) {
					echo "<td class=\"hlavicka\">". $value ."</td>";
					$count++;
				}

				?>
				
			</tr>
			<tr>
				<td><input type="checkbox"> </td>
				<?php
					for ($i=1; $i <= $count ; $i++) { 
						$vypis="value_".$i;
						echo "<td>".$select[$vypis]->control."</td>";
					}
				?>
			</tr>

		</table>
		<div id="tlacitka">
			<div class="buttons" id="add"><?php switch ($_GET["user"]) {
													case 'aranzer':	
														echo $buttons['add_song']->control;
														break;
													case 'personalista':
														echo $buttons['add_human']->control;
														break;
													case 'nastrojar':
														echo $buttons['add_instrument']->control;
														break;
													default:
														# code...
														break;
												}
										 ?></div>
			<div class="buttons" id="alter"><?php switch ($_GET["user"]) {
													case 'aranzer':	
														echo $buttons['alter_song']->control;
														break;
													case 'personalista':
														echo $buttons['alter_human']->control;
														break;
													case 'nastrojar':
														echo $buttons['alter_instrument']->control;
														break;
													default:
														# code...
														break;
												}
										 ?></div>
			<div class="buttons" id="delete"><?php switch ($_GET["user"]) {
													case 'aranzer':	
														echo $buttons['delete_song']->control;
														break;
													case 'personalista':
														echo $buttons['delete_human']->control;
														break;
													case 'nastrojar':
														echo $buttons['delete_instrument']->control;
														break;
													default:
														# code...
														break;
												}
										 ?></div>

		</div>
	
</body>
</html>
