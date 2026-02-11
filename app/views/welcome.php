<?php
use app\models\ObjetModel;
$model = new ObjetModel(Flight::db());
var_dump($model->getAllObjet());
?>
<h1>Welcome to the FlightPHP Skeleton Example!</h1>
<?php if(!empty($message)) { ?>
<h3><?=$message?></h3>
<?php } ?>