<?php

error_reporting(E_ALL & ~E_NOTICE);

include_once("../models/meekrodb.class.php");
include_once("../models/enjin.php");
include_once("../models/user.php");
include_once("message.php");
include_once("util.php");

use Pimple\Container;
$c = new Container();


#// figure out what we need to load
  if (isset($_GET['controller']) && isset($_GET['action'])) {
    $c["currentPage"] = $_GET['controller'];
    $c["currentAction"] = $_GET['action'];
  } else {
    $c["currentPage"] = 'users';
    $c["currentAction"] = 'home';
  }




$c["siteURL"]      = "http://www.fatalaggressionboss.com";
$c["enjinAPIKey"]  = "8a50f9619f2918e27cd1aeb99a48ab33e5d51ed09fa01b74";
$c["cacheTTL"]     = 600;
$c["cacheDIR"]     = "/cache";


$c["enjin"] = function  ($c) {
    return new enjinAPI($c);
};

$c["Messages"] = function ($c) {
    return new Messages($c);
};

$c["Util"] = function ($c) {
    return new Util($c);
};

$c["db"] = function ($c) {
    return new MeekroDB();
};

$c["User"] = function ($c) {
    return new User($c);
};
