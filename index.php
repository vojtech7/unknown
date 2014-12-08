<!-- vvvvvvvvvvvvvvvvvvvvvvvv HTML vvvvvvvvvvvvvvvvvvvvvvvv -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styl.css">
		<meta charset="iso-8859-2">
		<script src="js/libs/jquery-2.1.1.js"></script>
		<script src="js/filter.js"></script>
		<script type="text/javascript" src="netteForms.js"></script>
		<title>Filharmonie Liptákov</title>
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
				// echo "Vítejte v informačním systému Filharmonie Liptákov!<br>";
	    	echo '<div id="leftPanel">
								<span id="IS"><p>Informační systém</p></span><br>
								<span id="prihlasit"><p>Přihlásit se jako:</p></span><br>
					    	<div id="menu"><ul>
						      <li><a href="?page=manazer.php">Manažer</a></li>
						      <li><a href="?page=personalista.php">Personalista</a></li>
						      <li><a href="?page=hudebnik.php">Hudebník</a></li>
						      <li><a href="?page=aranzer.php">Aranžér</a></li>
						      <li><a href="?page=nastrojar.php">Nástrojář</a></li>
						      <li><a href="?page=admin.php">Administrátor</a></li></ul>
					      </div>
				      </div>';
				echo '<div id="rightArea">
								<span id="FL"><p>Filharmonie Liptákov</p></span>
							</div>';
			}
		?>
	</body>
</html>
