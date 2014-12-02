<?php
require 'Nette/loader.php';

use Nette\Forms\Form;
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
<?php
$form = new Form;
// $form->setAction('/submit.php');
// $form->setMethod('GET');

$form->addText('name', 'Jméno:')
  ->addRule(Form::FILLED, 'Zadejte jméno');
  
$form->addText('age', 'Věk')
  ->setType('number')    //toto se mi nelíbí :( dělá to tam hnusný šipky
  ->addRule(Form::INTEGER, 'Věk musí být číslo')
  ->addRule(Form::RANGE, 'Věk musí být od %d do %d', array(18, 120));
  
$form->addText('email', 'E-mail')
  ->addRule(Form::EMAIL,  'Zadejte email!');
  
$form->addText('phone', 'Telefon')
  ->addRule(Form::PATTERN, 'zadej 9 číslic', '([0-9]\s*){9}');
  
$form->addPassword('password', 'Heslo:')
  ->addRule(Form::MIN_LENGTH, 'Zadejte heslo', 1) //setRequired nefunguje, toto je náhrada
  ->addRule(Form::MIN_LENGTH,'Heslo musí mít lespoň %d znaky', 4);
  
$form->addPassword('passwordVerify', 'Kontrola hesla:')
  ->addRule(Form::MIN_LENGTH, 'Zadejte heslo ještě jednou', 1)
  ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);

/*
*záhadně to nefunguje
$form->addUpload('avatar', $title=NULL, $multiple = FALSE)
  ->addRule(Form::IMAGE, 'Avatar musí být JPEG, PNG nebo GIF.')
    ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 64 kB.', 64 * 1024 );
*/
  
$form->addCheckbox('agree', 'Souhlasím s podmínkami')
  ->addRule(Form::EQUAL, 'Je potřeba souhlasit s podmínkami', TRUE);
  
  $country=array(
    'CZ' => 'Czech',
    'GB' => 'United Kingdom',
    'D' => 'Germany'
  );
$form->addRadioList('zeme', 'Země',$country)
  ->getSeparatorPrototype()->setName(NULL);
  
  $countries = array(
  'Europe' => array(
    'CZ' => 'Česká Republika',
    'SK' => 'Slovensko',
    'GB' => 'Velká Británie',
  ),
  'CA' => 'Kanada',
  'US' => 'USA',
  '?'  => 'jiná',
);

$form->addSelect('country', 'Země:', $countries)
  ->setPrompt('Zvolte zemi')
  ->setItems($countries, FALSE);
  
$form->addMultiselect('options', 'možnosti', $country);
  
$form->addSubmit('send', 'Registrovat');
?>
<script src="netteForms.js"></script>
<style>
.required label { color: maroon }
</style>
<?php
echo $form; // vykreslí formulář

$sub1 = $form->addContainer('first');




if ($form->isSuccess()) {
  echo 'Formulář byl správně vyplněn a odeslán';
    $values = $form->getValues();
  dump($values);
}

//include "connect.php";
if(!empty($_POST)) {
  $jmeno = $_POST["name"];
  $vek = $_POST["age"];
  $email = $_POST["email"];
  // if(empty($_POST["n_jm_lyz"]) || empty($_POST["n_cn_lyz"])) echo "Nezadali jste všechny nové údaje.";
  // else { 
  //   pg_exec($connect,"update lyze set zn_mod='$n_jm_lyz' where id_l=$id_lyz");
  //   pg_exec($connect,"update lyze set cena='$n_cn_lyz' where id_l=$id_lyz");
  // }
  echo "jmeno: " . $jmeno . "<br>";
  echo "vek: " . $vek . "<br>";
  echo "email: " . $email . "<br>";
}

?>