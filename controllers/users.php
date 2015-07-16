<?php
include("../helpers/assign.php");

if (!$c["User"]->isLoggedIn()) {
   $c["Util"]->redirect("login");
}


$data["ranks"] =  $c["enjin"]->getTags();


require('../helpers/render.php');
?>