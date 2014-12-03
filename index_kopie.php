<script type="text/javascript" src="netteForms.js"></script>

<?php


require 'Nette/loader.php';
use Nette\Forms\Form;

	include "connect.php";

$moznosti_aranzer = array('ID skladby', 'Název','Délka','ID autora' );


$select = new Form;

$select->addText('value_1');
$select->addText('value_2');
$select->addText('value_3');
$select->addText('value_4');
$select->addSubmit('find_song', 'Vyhledat skladbu');

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/styl.css">
<? header("Content-Type: text/html; charset=UTF-8");?>
<title>Filharmonie Liptákov</title>




	

</head>
<body>
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
				
	
<div class="buttons" id="vyhledat"><?php echo $select['find_song']->control;?>
		
<?php	
if ($select->isSuccess()) {
	echo 'Form was submitted and successfully validated';
	dump($select->getValues());
	exit;
}
?>
</body>
</html>
