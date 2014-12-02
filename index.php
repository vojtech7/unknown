<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styl.css">
<? header("Content-Type: text/html; charset=UTF-8");?>
<title>Filharmonie Liptákov</title>



<?php
require 'Nette/loader.php';
use Nette\Forms\Form;
?>

<?php
	include "connect.php";
?>

</head>
<body>
		<?php
			$buttons = new Form;
			$buttons->addButton('logout', 'Odhlásit');
			if ($_GET["user"] == "aranzer") {
				$buttons->addButton('find_song', 'Vyhledat skladbu');
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
				$buttons->addButton('find_instrument', 'Vyhledat zamìstnance');
				$buttons->addButton('add_instrument', 'Pøidat zamìstnance');
				$buttons->addButton('alter_instrument', 'Upravit zamìstnance');
				$buttons->addButton('delete_instrument', 'Odstranit zamìstnance');
			}
		?>
		<div id="logout" class="buttons"><?php echo $buttons['logout']->control ?></div>

		<?php
			$moznosti_aranzer = array('ID skladby', 'Název','Délka','ID autora' );
			$moznosti_personalista = array('Rodné èíslo', 'Jméno', 'Pøíjmení');
			$moznosti_nastrojar = array('Výrobní èíslo', 'Datum výroby', 'Výrobce', 'Datum poslední revize', 'Datum poslední výmìny', 'Vymìnìno');
			

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
						echo "Filtry pro vyhledávání zamìstnancù";
						break;
					case "nastrojar" :
						echo "Filtry pro vyhledávání nástrojù";
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
				<?php
					switch ($_GET["user"]) {
						case 'aranzer':
							$tabulka = "Skladba";
							break;
						case 'personalista':
							$tabulka = 
							break;
						default:
							# code...
							break;
					}

					/*tahání dat z databáze*/
					$sql = "select * from Skladba";
					$hudebnici = mysql_query($sql);

					for ($i=0; $i < mysql_num_rows($hudebnici); $i++) { 
					  $row = mysql_fetch_row($hudebnici);
					  echo "<tr>";
					  echo "<td><input type=\"checkbox\"></td>";
					  for ($j=0; $j < mysql_num_fields($hudebnici); $j++) { 
					    echo "<td>".$row[$j];"</td>";
					  }
					  echo "</tr>";
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
