<?php
include("../helpers/assign.php");

if (!$c["User"]->isLoggedIn()) {
   $c["Util"]->redirect("login");
}


require_once ('../models/raffle.php');

$raffle = new raffle($c);


require('../helpers/render.php');

?>
