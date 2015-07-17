<?php
include ('../helpers/assign.php');

#// contains methods that output json objects for use in ajax requests

$method = $_REQUEST["method"];

$raffleAjax = new RaffleAjax($c);

$raffleAjax->$method();



class RaffleAjax {
    var $c;

    function __construct($c) {
        $this->c = $c;

    }


    function getGw2ItemsJSON() {
        $search = $_GET['query'];


        $result = DB::query("SELECT * FROM gw2_items where name like %ss order by locate(%s, name)", $search, $search);

        foreach ($result as $r) {
            $r["tp_price_fmt"] = $this->c["Util"]->formatgw2currency($r["tp_price"]);
            $return[] = $r;
        }

        $json = json_encode($return);
        print_r($json);
    }


    function addPrize() {
        $id = $_GET['id'];
        $raffle_id = $_GET['raffle_id'];

        DB::insert('raffle_prizes', array("item_id"=>$id, "raffle_id"=>$raffle_id));

    }

    function removePrize() {
        $prize_id = $_GET['id'];
        DB::delete('raffle_prizes', "prize_id=%i", $prize_id);
    }
    
    function deleteRaffle() {
        $raffle_id = $_GET['raffle_id'];
        DB::delete('raffle', "raffle_id=%i", $raffle_id);
    }
    
    function formatgw2currentcy() {
        $amt = $_GET['amt'];

        echo $this->c["Util"]->formatgw2currencty($amt);
    }

    function addEntry() {
        $user_id   = $_GET['user_id'];
        $amt       = $_GET['amt'];
        $raffle_id = $_GET['raffle_id'];

        DB::insert('raffle_entries', array(
                    "user_id"=>$user_id,
                    "entry_date"=>date("m/d/Y"),
                    "raffle_id"=>$raffle_id,
                    "entry_amount"=>$amt
                  ) );
    }
    function removeEntry() {
        $user_id   = $_GET['user_id'];
        $raffle_id = $_GET['raffle_id'];

        DB::query('delete from raffle_entries where raffle_id = %i and user_id = %i', $raffle_id, $user_id);
    }




}