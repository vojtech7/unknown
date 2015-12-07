<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styl.css">
		<link href="css/form.css" rel="stylesheet">
		<meta charset="iso-8859-2">
		<script type="text/javascript" src="js/netteForms.js"></script>
		<script src="js/libs/jquery-2.1.1.js"></script>
		<script src="js/filter.js"></script>
		<script src="js/form.js"></script>
		<style> .required label { color: maroon } </style>
		<title>Personalista Filharmonie Liptákov</title>
	</head>
<body>

	<!-- uvodni inicializace -->
	<?php
		// require 'Nette/loader.php';
		// use Nette\Forms\Form;

		include "connect.php";
		use Nette\Forms\Form;
		session_save_path("./tmp");
		session_start();
		$role = 'personalista';
		//uzivatel neni prihlasen
		//if(!isset($_SESSION['logged_in']) or $_SESSION['role'] != $role) {
		if(0){
			echo "
			<form action='login.php?page=$role.php' method='post' enctype='multipart/form-data'>
				<h3>Pøihlá¹ení</h3>
				Login:<input type='text' name='login'><br>
				Heslo:<input type='password' name='heslo'>
				<input type='submit' value='Pøihlásit'>         
			</form>";
		}

		//timeout
		// elseif(time() - $_SESSION['timestamp'] > 900) {
		// 	session_destroy();
		// 	header("Location:timeout.php");
		// }

		//uzivatel je prihlasen, tohle else je az do konce souboru
		else {
		 $tabulka_uprav = "Hudebnik";
		 $nadpisy_sloupcu = array('Jméno', 'Pøíjmení', 'Rodné èíslo');
		 $nazvy_sloupcu = array('jmeno', 'prijmeni', 'rodne_cislo');
		 $pk = "rodne_cislo";
		 $nadpis_vysledku = "Seznam hudebníkù";
		 $page = "personalista.php";
		 echo "<div id=logout_btn><a href='logout.php'>Odhlásit se</a></div>";
		 echo '<div id="menu"><ul>';
		 // echo "<ul><li><a href='P_add_form_show()'>Pøidat zamìstnance</a></li>";
		 echo "<button onclick='P_add_form_show(\"$role\")'>Pøidat zamìstnance</button>";
		 echo "</ul><div>";


		//tabulka se vstupy pro hledani
			echo '<table id="hledani" class="pattern">
						<span class="nadpis" id="nadpis_vyhledavani">Filtry pro vyhledávání nástrojù</span>
						<tr>';
				foreach ($nadpisy_sloupcu as $value) {
					echo "<td>". $value ."</td>";
				}
				echo "</tr>";
				echo "<tr>";
				foreach ($nazvy_sloupcu as $value) {
					echo "<td> <input type=\"text\" class=\"form-control filter_". $value ."\"></td>";
				}
				echo "</tr>";
				echo "</table>";

				//tabulka pro zobrazovani vysledku hledani
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

				//pred zobrazenim radku se provedou pripadne SQL dotazy nad tabulkou
				//odstraneni hudebnika z tabulky
				if(isset($_GET['delete'])) {
					$delete_row = "DELETE FROM ".$tabulka_uprav." WHERE ".$pk.'="'.$_GET['delete'].'";';
					$delete_success = mysql_query($delete_row);
					if(!$delete_success) echo "nepodarilo se odstranit polozku";
					header("Location:personalista.php");
				}
				//pridani nebo uprava radku tabulky
				if(isset($_GET["jmeno"]) and isset($_GET["prijmeni"]) and isset($_GET["rodne_cislo"]) and isset($_GET["heslo"]) and isset($_GET["edit"])) {
					$jmeno = $_GET["jmeno"];
					$prijmeni = $_GET["prijmeni"];
					$rodne_cislo = $_GET["rodne_cislo"];
					$heslo_hash = sha1($_GET['heslo']);

					if ($_GET["edit"]=="edit") {
						$sql="UPDATE $tabulka_uprav SET jmeno = '$jmeno', prijmeni ='$prijmeni', rodne_cislo='$rodne_cislo', heslo_hash='$heslo_hash' WHERE rodne_cislo='".$_GET["PK_old"]."'";
						$success = mysql_query($sql);
						//echo $sql;
						if(!$success) $error = "nepodarilo se upravit polozku";	
						header("Location:personalista.php");
					}
					else {
						$sql = "INSERT INTO $tabulka_uprav VALUES ('$rodne_cislo', '$jmeno', '$prijmeni', '$heslo_hash');";
						$success = mysql_query($sql);
						if(!$success) $error =  "nepodarilo se vlozit polozku";	
            header("Location:vyber_nastroje_hud.php?rc_hud=$rodne_cislo");
					}
					
					if (isset($error)) {
						echo $error;
					}
				}

				/*tahani dat z databaze*/
				$sql = "select * from ".$tabulka_uprav;
				$vysledek = mysql_query($sql);
				$columns_count = count($nazvy_sloupcu);

				/*
					$alter = hodnoty všech sloupců tabulky oddělené vlnovkou ~~
					předává se do formuláře pro úpravu skladby
				*/
				$alter="";
				
				//vykresleni radku a sloupcu s vysledky
				while($row = mysql_fetch_array($vysledek)){
					echo "<tr>";
					for ($i=0; $i < $columns_count; $i++) { 
						if($nazvy_sloupcu[$i] === "rodne_cislo")							
							echo "<td class='filter_{$nazvy_sloupcu[$i]}'><a href='hud_detail.php?rc_hud={$row['rodne_cislo']}'>{$row[$nazvy_sloupcu[$i]]}</a></td>";
						else
							echo "<td class='filter_{$nazvy_sloupcu[$i]}'>{$row[$nazvy_sloupcu[$i]]}</td>";
						$alter = $alter.$row[$i]."~~";
					}
					//predam si PK do url parametru delete
					echo "<td id=delete_btn><a href='?page={$page}&delete={$row[$pk]}'>Odstranit</a></td>";
					$alter="\"".$alter."\"";
					echo "<td class=alter_btn><button onclick='P_alter_form_show($alter, \"$role\")'>Upravit</button></td>";
					$alter="";
					echo "</tr>";
				}

				echo "</tr>";
				echo "</table>";

			echo '</div>
						<!-- formular pro pridani -->
						<div id="P_add_form" class="abc">
						<!-- Popup Div Starts Here -->
						<div id="popupContact">
						<!-- Contact Us Form -->
						<img id="close" src="img/close-icon.png" onclick ="P_add_form_hide()">

						<!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->';
	require 'Nette/loader.php';

	//use Tracy\Debugger;
	//Debugger::enable(); // aktivujeme Ladìnku
	require_once 'Nette/Forms/Form.php';

		$form = new Form;
		$form->setAction('personalista.php');
		$form->setMethod('GET');

		$form->addText('jmeno', 'Jmeno:')
			->addRule(Form::FILLED, 'Zadejte jmeno');
		$form->addText('prijmeni', 'Prijmeni:')
			->addRule(Form::FILLED, 'Zadejte prijmeni');
		$form->addText('rodne_cislo', 'Rodne cislo:')
			->addRule(Form::FILLED, 'Zadejte rodne cislo')
	    ->addRule(Form::PATTERN, 'Rodne cislo musi byt ve tvaru xxxxxx/xxxx', '\d{6}/\d{4}')
			->setAttribute('placeholder', 'xxxxxx/xxxx');
    $form->addPassword('heslo', 'Heslo:')
      ->addRule(Form::FILLED, 'Zadejte heslo')
      ->addRule(Form::MIN_LENGTH,'Heslo musi mit alespon %d znaky', 4);
		$form->addHidden('edit');
		$form->addHidden('PK_old');
		$form->addSubmit('send', 'Pridat');

	echo $form; // vykresli formular

	$sub1 = $form->addContainer('first');

	if ($form->isSuccess()) {
		// header("Location:$page");
		// echo 'Formuláø byl správnì vyplnìn a odeslán';
		// 	$values = $form->getValues();
		// dump($values);
	}

	echo '
	</div>
	<!-- Popup Div Ends Here -->
	</div>
	<!-- Display Popup Button -->
	<!-- <button id="popup" onclick="P_add_form_show()">Popup</button> -->';
	}//uzivatel je prihlasen
	?>
		
	</body>
</html>
