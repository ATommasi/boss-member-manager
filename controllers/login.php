<?php
include("../helpers/assign.php");



if ($_REQUEST["action"] == "logout")  {
   $c["User"]->Logout();

} elseif (isset($_POST['email'])) {
   if ($c["User"]->Login($_POST["email"], $_POST["password"])) {
        $c["Util"]->redirect("users");
    }

}

if ($c["User"]->IsLoggedIn()) {
    $c["Util"]->redirect("users");
}

include("../helpers/render.php");
