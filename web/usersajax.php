<?php
include ('../helpers/assign.php');

#// contains methods that output json objects for use in ajax requests

$method = $_REQUEST["method"];

$UserJSON = new UserAjax($c);

$UserJSON->$method();

class UserAjax {
    private $c;

    function __construct($c) {
        $this->c = $c;
    }

    function getByTag() {

        $tag = $_REQUEST["tag"];

        $return = array();

        #die('{"0":{"joinDate":"01\/02\/2015","days_old":147,"username":"ArcaneChaos"},"1":{"joinDate":"04\/17\/2015","days_old":42,"username":"Jokus"},"2":{"joinDate":"03\/09\/2015","days_old":81,"username":"Red"},"3":{"joinDate":"01\/27\/2015","days_old":122,"username":"Porthos Du Valon"},"4":{"joinDate":"03\/04\/2015","days_old":86,"username":"Firecrotch"},"5":{"joinDate":"03\/09\/2015","days_old":81,"username":"Ciana"},"6":{"joinDate":"05\/17\/2015","days_old":12,"username":"Frisco"},"7":{"joinDate":"01\/03\/2015","days_old":146,"username":"SeekerOfSouls"},"8":{"joinDate":"04\/29\/2015","days_old":30,"username":"LeoDeSol.7604"},"9":{"joinDate":"05\/04\/2015","days_old":25,"username":"Gianna.2013"}}');

        $members = $this->c["enjin"]->getMembersByTag($tag);
        foreach ($members as $mid=>$m) {
            #--$userStats = $enjin->getStats($mid);
            $userStats = $this->c["db"]->queryFirstRow("select account_id, ts3_username, application_id from users where enjin_username = %ls ", [$m["username"]]);

            if (!empty($tag) && $tag <> 'all') {
                $this->c["db"]->update('users', array(
                        'tag_id' => $tag
                        ), "enjin_username=%ls", [$m["username"]]);

                $currentRank = $tag;
            }

            $i = count($listing);

            #$joinDate = strtotime("@".$m["datejoined"])df
            $listing[$i]["action"]  =  "<div class='btn-group' role='group'>".$this->showSetRankMenu($mid, $m) . $this->showActionMenu($mid, $m, $userStats["application_id"])."</div>";
            $listing[$i]["accountid"] = $userStats["account_id"];
            $listing[$i]["joinDate"] = date("m/d/Y", strtotime("@".$m["datejoined"]));
            $listing[$i]["days_old"] = round(abs(strtotime("now")- strtotime("@".$m["datejoined"]))/86400);
            $listing[$i]["username"] = $this->buildCharMenu($m["characters"]).$m["username"];
            $listing[$i]["ts3_user"] = $userStats["ts3_username"];
            $listing[$i]["application_id"] = $userStats["application_id"];
            $listing[$i]["user_id"] = $mid;



        }

        echo json_encode($listing);
    }

    function setNewRank() {
        $userID = $_REQUEST["userid"];
        $tagID = $_REQUEST["tagid"];

        $this->c["enjin"]->setNewRank($userID, $tagID);
    }

    private function showSetRankMenu($mid, $m) {

        #$ranks = $this->c["enjin"]->getTags();
        $ranks = $this->c["db"]->query("select * from rank_tags order by rank_order");
        $RankMenuItems = array();

        #// build the rank promotion menu items
        for ($x = 1; $x <= count($ranks); $x++) {
            if ($ranks[$x]["tag_id"] == $m["currentRank"]) continue;

            $li = "<li role='presentation'><a role='menuitem' tabindex='-1' newRank='".$ranks[$x]["tag_id"]."' memberID='".$mid."' class='user_promote' href='#'>".$ranks[$x]["rank_name"]."</a></li>";
            if ($ranks[$x-1]["tag_id"] == $m["currentRank"]) {
                array_unshift($RankMenuItems, "<li role='presentation' class='divider'></li>");
                array_unshift($RankMenuItems, $li);
            } else {
                $RankMenuItems[] = $li;
            }
        }


        $html = <<<EOT
        <div class='memberaction dropdown btn-group invisible'>
            <button class="btn btn-default dropdown-toggle" type="button" id="menu_<?=$mid?>" data-toggle="dropdown" aria-expanded="true" title="Set Member Rank">
                <img src='/images/tag_edit.png' width='20'>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu_<?=$mid?>">

                <li role="presentation" class="dropdown-header">Set new rank</li>
EOT;
        foreach ($RankMenuItems as $menuItem) {
            $html .= $menuItem;
        }

        $html .= '<li role="presentation"><a role="menuitem" tabindex="-1" memberID="'.$mid.'" class="user_guildkick" href="#">No Longer in Guild</a></li>';

        $html .= '</ul>
            </div>';

        return $html;
    }


    function findUser() {
        $search = $_GET["query"];
        
        $sql = "select distinct users.user_id, account_id, enjin_username username
            from users left outer join user_chars on users.user_id = user_chars.user_id
            where account_id like '%$search%'
              or enjin_username like '%$search%'
              or ts3_username like '%$search%'
              or char_name like '%$search%'
            ";
        
        $result = DB::query($sql);
        
        
        echo json_encode($result);
        
        
    }


    private function showActionMenu($mid, $m, $appid) {
       $html = <<<EOT
        <div class='memberaction dropdown btn-group invisible'>
            <button class="btn btn-default dropdown-toggle" type="button" id="menu_<?=$mid?>" data-toggle="dropdown" aria-expanded="true" title="Action">
                    <img src='/images/cog.png' width='20'>

            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu_<?=$mid?>">

                <li role="presentation" class="dropdown-header">Action</li>
EOT;

        $html .='<li role="presentation"><a href="/viewprofile"  class="showmodal">View User Profile</a></li>';
        $html .='<li role="presentation"><a href="/viewapp?app_id='.$appid.'"  class="showmodal">View Application</a></li>';


           $html .= '</ul>
            </div>';

        return $html;

    }




    function updateAccountID() {
        $user_id = $_REQUEST["user_id"];
        $accountid = $_REQUEST["accountid"];

        if ($user_id  == 'null') $user_id = "";

        $this->c["db"]->update('users', array(
            'account_id' => $accountid
            ), "user_id=%i", $user_id);

    }


    private function buildCharMenu($chars) {


        if (!is_array($chars[1374])) {
            //echo "<pre>"; print_r($chars);
            return;
        }
        foreach($chars[1374] as $c) {
            $nameArray[] = htmlspecialchars ("<img width=\"16\" src=\"/images/classes/".$c["type"].".png\">").$c["name"];
            $hiddenArray[] = $c["name"];
        }
        $charHTML = join("<br>&nbsp;", $nameArray);
        $hiddenSpan = join("", $hiddenArray);
        return "<span class='hidden'>".$hiddenSpan."</span><a  title='Character List' rel='popover' data-placement='bottom' data-content='".$charHTML."' href='#'>
        <span class='glyphicon glyphicon-user' style=' font-size: 10px'></span><span class='caret'></span></a>&nbsp;&nbsp;";

        #return '<button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">  Popover on left </button>';
    }
}






?>