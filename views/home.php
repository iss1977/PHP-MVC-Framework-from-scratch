<?php

global $number;
$number++;

$_SESSION["number"]++;

$otherNumber = $_SESSION["number"] ;


echo '<h1>Home</h1>';

echo 'Number is '.$number;
echo 'Other number is '.$otherNumber;