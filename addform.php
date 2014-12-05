<!DOCTYPE html>
<html>
<head>
<title>Popup contact form</title>
<link href="css/form.css" rel="stylesheet">
<script src="js/form.js"></script>
</head>
<!-- Body Starts Here -->
<body id="body">
<div id="abc">
<!-- Popup Div Starts Here -->
<div id="popupContact">
<!-- Contact Us Form -->

<!-- vvvvvvvvvvvvv Nette Form  vvvvvvvvvvvvv -->
<?php
require 'Nette/loader.php';

use Nette\Forms\Latin2Form;
require_once 'Nette/Forms/Latin2Form.php';
?>
<!--
select {
  background-color: #FDFBFB;
  border: 1px #BBBBBB solid;
  padding: 2px;
  margin: 1px;
  font-size: 14px;
  color: #808080;
}
-->
<img id="close" src="images/3.png" onclick ="history.back(-1)">

<?php
$form = new Latin2Form;
$form->setAction('index.php?user=personalista');
$form->setMethod('GET');

/*****************************************************************/
/*  Formular pro personalistu, pro pridani hudebnika.            *
 *  Slo by ho dat do index.php, pres css display:none/block.     *
 *  Nevim ale, jestli by se po submitu skryl.                    *
 */
// switch ($_GET['user']) {
//   case 'personalista':
    $form->addText('jmeno', 'Jm�no:')
      ->addRule(Latin2Form::FILLED, 'Zadejte jm�no');
    $form->addText('prijmeni', 'P��jmen�:')
      ->addRule(Latin2Form::FILLED, 'Zadejte p��jmen�');
    $form->addText('rodne_cislo', 'Rodn� ��slo:')
      ->addRule(Latin2Form::FILLED, 'Zadejte rodn� ��slo');
//     break;
  
//   default:
//     # code...
//     break;
// }
  
// $form->addText('age', 'Věk')
//   ->setType('number')    //toto se mi nelíbí :( dělá to tam hnusný šipky
//   ->addRule(Latin2Form::INTEGER, 'Věk musí být číslo')
//   ->addRule(Latin2Form::RANGE, 'Věk musí být od %d do %d', array(18, 120));
  
// $form->addText('email', 'E-mail')
//   ->addRule(Latin2Form::EMAIL,  'Zadejte email!');
  
// $form->addText('phone', 'Telefon')
//   ->addRule(Latin2Form::PATTERN, 'zadej 9 číslic', '([0-9]\s*){9}');
  
// $form->addPassword('password', 'Heslo:')
//   ->addRule(Latin2Form::MIN_LENGTH, 'Zadejte heslo', 1) //setRequired nefunguje, toto je náhrada
//   ->addRule(Latin2Form::MIN_LENGTH,'Heslo musí mít lespoň %d znaky', 4);
  
// $form->addPassword('passwordVerify', 'Kontrola hesla:')
//   ->addRule(Latin2Form::MIN_LENGTH, 'Zadejte heslo ještě jednou', 1)
//   ->addRule(Latin2Form::EQUAL, 'Hesla se neshodují', $form['password']);


// *záhadně to nefunguje
// $form->addUpload('avatar', $title=NULL, $multiple = FALSE)
//   ->addRule(Latin2Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
//     ->addRule(Latin2Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 64 kB.', 64 * 1024 );

  
// $form->addCheckbox('agree', 'Souhlasím s podmínkami')
//   ->addRule(Latin2Form::EQUAL, 'Je potřeba souhlasit s podmínkami', TRUE);
  
//   $country=array(
//     'CZ' => 'Czech',
//     'GB' => 'United Kingdom',
//     'D' => 'Germany'
//   );
// $form->addRadioList('zeme', 'Země',$country)
//   ->getSeparatorPrototype()->setName(NULL);
  
//   $countries = array(
//   'Europe' => array(
//     'CZ' => 'Česká Republika',
//     'SK' => 'Slovensko',
//     'GB' => 'Velká Británie',
//   ),
//   'CA' => 'Kanada',
//   'US' => 'USA',
//   '?'  => 'jiná',
// );

// $form->addSelect('country', 'Země:', $countries)
//   ->setPrompt('Zvolte zemi')
//   ->setItems($countries, FALSE);
  
// $form->addMultiselect('options', 'možnosti', $country);
  
$form->addSubmit('send', 'P�idat');
?>

<script src="netteForms.js"></script>
<style>
.required label { color: maroon }
</style>

<?php
echo $form; // vykreslí formulář

$sub1 = $form->addContainer('first');


if ($form->isSuccess()) {
  echo 'Formul�� byl spr�vn� vypln�n a odesl�n';
    $values = $form->getValues();
  dump($values);
}


?>
<!-- ^^^^^^^^^^^^^ Nette Latin2Form  ^^^^^^^^^^^^^ -->

</div>
<!-- Popup Div Ends Here -->
</div>
<!-- Display Popup Button -->
<!-- <h1>Click Button To Popup Form Using Javascript</h1>
<button id="popup" onclick="div_show()">Popup</button>
</body> -->
 <!-- Body Ends Here -->
</html>
