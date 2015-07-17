<?php
    require_once('../templates/default/header.php');
    require_once('../templates/default/messages.php');

    #include_once("/helpers/routes.php");
    include_once("../templates/default/".$c["currentPage"].".php");
    require_once('../templates/default/debug_messages.php');
    require_once('../templates/default/footer.php');

?>