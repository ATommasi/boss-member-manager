<?php
include("../helpers/assign.php");
#$page = substr($_SERVER["REQUEST_URI"],1);

if (!$c["User"]->isLoggedIn() && $c["currentPage"] <> 'login') {
   $c["Util"]->redirect("login");
}


$c["Util"]->loadController($c["currentPage"]);
#---------------------------------------------
