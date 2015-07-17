<?php
require_once('jsonrpc.php');
#include("../helpers/assign.php");

use JsonRPC\Client as JsonClient;

class enjinAPI extends JsonClient {

    private $apiKey = NULL;
    private $c;

    function __construct($c) {

        $this->apiKey = $c["enjinAPIKey"];
        $this->c = $c;

        parent::__construct($c["siteURL"]."/api/v1/api.php");


    }


    function getStats($userid) {

        return $this->execute("Stats.get", array("api_key"=>$this->apiKey, "user_id"=>$userid));
    }

    function syncRanks() {
        $this->c["db"]->query("truncate table rank_tags;");
        $ranks = $this->getTags();

        foreach ($ranks as $r) {
            $rank_rows[] = array(
                     "tag_id" => $r["tag_id"],
                     "rank_name" => $r["tagname"],
                     "rank_order" => count($rank_rows)+1
                     );
        }
        $this->c["db"]->insert("rank_tags", $rank_rows);

    }

    function getTags() {
        if (apc_exists('ranks')) {
        #if (false) {
            $return = apc_fetch('ranks');
        } else {
            $ranks =  $this->execute("Tags.getTagTypes", array("api_key"=>$this->apiKey));
            foreach ($ranks as $r) {
                if ($r["visible"] == 0) {
                    continue;
                }
                $return[] = $r;

            }
            apc_store("ranks", $return, $this->c["cacheTTL"]);
        }
        return array_reverse($return);
    }

    function getRankName($tag_id) {
        $name = $this->c["db"]->queryFirstField("select rank_name from rank_tags where tag_id = %s", $tag_id);
        return $name;

    }

    function setNewRank($userID, $tagID) {
        $currentRank = $this->getCurrentRank($userID);

        $this->execute("Tags.untagUser", array("api_key"=>$this->apiKey, "user_id"=>$userID, "tag_id"=> $currentRank));
        $this->execute("Tags.tagUser", array("api_key"=>$this->apiKey, "user_id"=>$userID, "tag_id"=> $tagID));
        $this->c["db"]->update('users', array(
                'tag_id' => $tagID
                ), "user_id=%i", $userID);

    }

    function getCurrentRank($userID) {
        $currentRank = $this->c["db"]->queryFirstField("select tag_id from users where user_id = %i", $userID);
        if ($this->c["db"]->count() == 0 ) {
            $memberInfo =  $this->execute("UserAdmin.get", array("api_key"=>$this->apiKey, "tag_id"=> $tagID, "user_id"=>$userID));

            #// user doesn't exist in database, so we'll add them here
            $currentRank = $tagID;
            $this->c["db"]->replace("users", array(
                "user_id" => $userID,
                "account_id" => "",
                "ts3_username" => "",
                "enjin_username" => $memberInfo[$userID]["username"],
                "application_id" => "",
                "application_submitdate" => $memberInfo[$userID]["joindate"],
                "tag_id" => $tagID
                )
            );
        }
        return $currentRank;
    }

    function getMembersByTag($tagID) {

        if ($tagID == "all") $tagID = "";

        $memberInfo =  $this->execute("UserAdmin.get", array("api_key"=>$this->apiKey, "characters"=>true, "tag_id"=> $tagID));


        foreach ($memberInfo as $id=>$info) {
            $memberInfo[$id]["currentRank"] = $this->getCurrentRank($id);
        }


        #echo "<pre>"; print_r($memberInfo);
        return $memberInfo;
    }
}



?>
