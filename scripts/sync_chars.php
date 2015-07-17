#!/usr/bin/php
<?php

#// this script will process recent applications
#// and update user profile fields (stats) with application data

include('../helpers/assign.php');
require_once "../models/enjin.php";
require_once "../models/jsonrpc.php";

$enjin = new enjinAPI($c);

$siteURL = "http://www.fatalaggressionboss.com";
$apiKey  = "8a50f9619f2918e27cd1aeb99a48ab33e5d51ed09fa01b74";
$sessionFile = "../config/enjinSession";

use JsonRPC\Client;


#$enjin->debug=true;

echo "\nStarting\n";



    #// need to get character information
    $userDetails = $enjin->execute("UserAdmin.get", array("api_key"=>$apiKey, "characters"=>true));

    foreach ($userDetails as $user_id => $user) {
      
      #// get character information
      #// delete any existing characters
      DB::query("delete from user_chars where user_id = %i", $user_id);
      
      $chars = $user["characters"]["1374"];
      
      
      #// insert new chars
      if (is_array($chars)) {
         $crows = array();
         foreach ($chars as $c) {
            $crows[] = array(
                  "user_id" => $user_id,
                  "char_name" => $c["name"],
                  "char_type" => $c["type"]
            );
         }
         
         DB:: insert("user_chars", $crows);
      }
      
 
    }