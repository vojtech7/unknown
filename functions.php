<?php
require 'Nette/loader.php';
use Nette\Forms\Form;
$select = new Form;

$select->addText('value_1');
$select->addText('value_2');
$select->addText('value_3');
$select->addText('value_4');
$select->addSubmit('submit');

if ($select->isSuccess()) {
	echo '<h2>Form was submited and successfully validated</h2>';
	dump($select->getValues(TRUE));
}

?>

<!DOCTYPE html>
<meta charset="utf-8">
<?php echo $select ?>