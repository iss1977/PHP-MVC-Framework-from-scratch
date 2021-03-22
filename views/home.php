<?php

global $number;
$number++;

$_SESSION["number"]++;

$otherNumber = $_SESSION["number"] ;


echo '<h1>Home</h1>';

echo 'Number is '.$number;
echo 'Other number is '.$otherNumber;

?>

<h1>The variable we send was<?php echo $name ?? 'Variable not present';?></h1>