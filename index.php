<!-- vvvvvvvvvvvvvvvvvvvvvvvv HTML vvvvvvvvvvvvvvvvvvvvvvvv -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styl.css">
		<meta charset="iso-8859-2">
		<script src="js/libs/jquery-2.1.1.js"></script>
		<script src="js/filter.js"></script>
		<script type="text/javascript" src="netteForms.js"></script>
		<title>Filharmonie Lipt�kov</title>
	</head>
	<body>
		<?php
			include "connect.php";
			//jestlize je vybran uzivatel
			if(isset($_GET["page"])) {
	      include $_GET['page'];
			}
			//neni vybran zadny uzivatel; obrazovka pro vyber role uzivatele
			//prihlasit se jako: manazer, hudebnik, personalista, nastrojar, aranzer
			else {
				// echo "V�tejte v informa�n�m syst�mu Filharmonie Lipt�kov!<br>";
	    	echo '<div id="leftPanel">
								<span id="IS"><p>Informa�n� syst�m</p></span><br>
								<span id="prihlasit"><p>P�ihl�sit se jako:</p></span><br>
					    	<div id="menu"><ul>
						      <li><a href="?page=manazer.php">Mana�er</a></li>
						      <li><a href="?page=personalista.php">Personalista</a></li>
						      <li><a href="?page=hudebnik.php">Hudebn�k</a></li>
						      <li><a href="?page=aranzer.php">Aran��r</a></li>
						      <li><a href="?page=nastrojar.php">N�stroj��</a></li>
						      <li><a href="?page=admin.php">Administr�tor</a></li></ul>
					      </div>
				      </div>';
				echo '<div id="rightArea">
								<span id="FL"><p>Filharmonie Lipt�kov</p></span>
							</div>';
			}
		?>
	</body>
</html>
