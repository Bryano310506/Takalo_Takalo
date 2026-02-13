<?php
$_SESSION["user"] = array();
$_SESSION["user"]["id"] = 1;
?>
<h1>Welcome to the FlightPHP Skeleton Example!</h1>
<?php if(!empty($message)) { ?>
<h3><?=$message?></h3>
<?php } ?>