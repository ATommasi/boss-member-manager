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
$appType = "approved";
#$appType = "archive";


use JsonRPC\Client;


#$enjin->debug=true;

echo "\nStarting\n";

#// must login first
#// login sessions last about 30 days...

$sessionID = trim(file_get_contents($sessionFile));

$sessionInfo = $enjin->execute("User.checkSession", [$sessionID]);


if ($sessionInfo["hasIdentity"] <> "1") {
   print "Session expired... getting a new one.\n";

   #// login and get a new session ID
   $login_result = $enjin->execute('User.login', array("email"=>'arcanechaos11@gmail.com', "password"=>'iLiagd11'));
   $sessionID = $login_result['session_id'];

   $sessionInfo = $enjin->execute("User.checkSession", [$sessionID]);

   file_put_contents($sessionFile, $sessionID);
} else {
   echo "Session still good..\n";
}

echo "Syncing ranks...\n";
$enjin->syncRanks();


#// get first page of apps
$applist = $enjin->execute('Applications.getList', array('session_id'=>$sessionID, 'type'=>$appType));

#// find out how many pages there are...
$perPage = count($applist["items"]);
$totapps = $applist['total'];

$totpages = ($perPage > 0 ? ceil($totapps/$perPage) : 0);


for ($curPage=1; $curPage <= $totpages; $curPage++) {
   foreach ($applist["items"] as $app) {
      
      echo "Processing app for ".$app["username"]." [AppID: ".$app["application_id"]."]... ";

      $appDetails = $enjin->execute('Applications.getApplication', array('session_id'=>$sessionID, 'application_id'=>$app["application_id"]));

      #// extract the extra user information from the application, and save it
      $UserInfo[$app["user_id"]] = array (
               "TS3 Username" => $appDetails["user_data"]["goqjf6cp8w"],
               "Account ID"   => $appDetails["user_data"]["7bts17biho"]
            );



      DB::update("users", array(
         "account_id" =>$UserInfo[$app["user_id"]]["Account ID"]
         ), "user_id=%i and account_id is null or account_id = ''", $app["user_id"]);
#
     #DB::update("users", array(
         #"ts3_username" =>$UserInfo[$app["user_id"]]["TS3 Username"]
         #), "user_id=%i and ts3_username is null or ts3_username = ''", $app["user_id"]);
      
      DB::insertUpdate("users", array(
            "user_id" => $app["user_id"],
            "account_id" => $appDetails["user_data"]["7bts17biho"],
            "ts3_username" => $appDetails["user_data"]["goqjf6cp8w"],
            "enjin_username" => $app["username"],
            "application_id" => $app["application_id"],
            "application_submitdate" => date("Y-m-d", $app["created"]),
            "avatar" => $app["avatar"]
            ), array(
             "enjin_username" => $app["username"],
            "application_id" => $app["application_id"],
            "application_submitdate" => date("Y-m-d", $app["created"]),
            "ts3_username" => $UserInfo[$app["user_id"]]["TS3 Username"],
            "avatar" => $app["avatar"]
            )
         );

      try  {
         $enjin->execute('Stats.saveUserStats', array('api_key'=>$apiKey,  'stats'=>$UserInfo));

         #// archive application
         $enjin->execute('Applications.archive', array('session_id'=>$sessionID,  'application_id'=>$app["application_id"]));




         echo "Done\n";

      } catch (Exception $e) {
         echo "Error\n";
      }










   }

   #// get next page of apps
   $applist = $enjin->execute('Applications.getList', array('session_id'=>$sessionID, 'type'=>$appType, 'page'=>$curPage+1));
}


?>
