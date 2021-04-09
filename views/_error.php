<?php 

// we call this from Application when we throw an exception. We gonna pass an array ['exception'=> $exceptionObject]
// and will reach the function renderOnlyView($view, $params) where we create the variable $exception based on the name 'exception' from ['exception'=> $exceptionObject]
/** @var  $exception \Exception*/

?>
<!--This is the generalized 404 page-->
<h3><?php echo $exception->getCode() ?> - <?php echo $exception->getMessage() ?></h3>