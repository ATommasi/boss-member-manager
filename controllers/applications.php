<?php
include("../helpers/assign.php");

if (!$c["User"]->isLoggedIn()) {
   $c["Util"]->redirect("login");
}




require('../helpers/render.php');
?>