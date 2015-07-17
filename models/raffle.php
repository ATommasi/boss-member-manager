<?php

class raffle{
    private $c;
    public $settings;
    private $raffle_id;

    function __construct ($c) {
        #// pull out objects we need from the container
        $this->c = $c;

        #// initialize the model by getting stored settings
        #//$this->settings = $this->getSettings();
    }


    function getCurrentRaffle() {
        return DB::queryFirstField(
                "select raffle_id from raffle
                where end_date >= curdate()
                 and start_date <= curdate()
                order by end_date");

    }
    function getSettings() {
        return $this->settings = DB::queryFirstRow("select * from raffle where raffle_id = %i", $this->raffle_id);
    }

    function setCurrentRaffle($rid) {
        $this->raffle_id = $rid;
        $this->getSettings();
    }

    function getRaffles() {
        #$raffles = DB::query("SELECT r.raffle_id, start_date, end_date, COUNT( e.* ) entries
                    #FROM raffle r left outer join raffle_entries e on e.raffle_id = r.raffle_id
                    #ORDER BY raffle_id, start_date, end_date
                    #LIMIT 0 , 30");



        $raffles = DB::query("SELECT r.raffle_id, r.start_date, r.end_date, r.max_entries, r.entry_fee , count( * ) entries, prize_value
                        FROM raffle_entries e
                        RIGHT JOIN raffle r on r.raffle_id = e.raffle_id
                        LEFT OUTER JOIN(
                            SELECT raffle_id,SUM(tp_price) as prize_value
                            FROM gw2_items, raffle_prizes
                            where item_id = id
                            GROUP BY raffle_id
                        ) prizes ON prizes.raffle_id=r.raffle_id
                        GROUP BY r.start_date, r.end_date, r.max_entries, r.entry_fee, prize_value");
        return $raffles;
    }


    public function getEntries() {
        $entries = DB::query("select r.*, enjin_username as username
                            from raffle_entries r, users u
                            where r.user_id = u.user_id
                              and r.raffle_id = ". $this->raffle_id);
        return $entries;
    }


    function getPrizes($rid="") {
        if (empty($rid) ) $rid = $this->raffle_id;

                
        $prizes = DB::query("select i.*, prize_id
                    from gw2_items i, raffle_prizes r
                    where r.item_id = i.id
                        and r.raffle_id = $rid
                        order by i.name");
        return $prizes;

    }

    function getGoldCollected() {
        return DB::queryFirstField("select sum(entry_amount)
                                   from raffle_entries
                                   where raffle_id = %i", $this->raffle_id);

    }


    function getTotalEntries($rid="") {
        if (empty($rid) ) $rid = $this->raffle_id;
        
        return DB::queryFirstField("select count(*)
                                   from raffle_entries
                                   where raffle_id = %i", $rid);

    }
    
    function getPrizeValue($rid="") {
        
        if (empty($rid) ) $rid = $this->raffle_id;
    
        return DB::queryFirstField("select sum(tp_price)
                                   from raffle_prizes p, gw2_items i
                                   where p.item_id = i.id
                                     and raffle_id = %i", $rid);   
    }
    
    function deleteRaffle($id) {
        DB::delete('raffle', "raffle_id=%i", $id);
    }

    function addRaffle(Array $r) {

        DB::insert('raffle', array(
                    "start_date"    =>$r["start_date"],
                    "end_date"      =>$r["end_date"],
                    "entry_fee"     =>$r["entry_fee"],
                    "max_entries"   =>$r["max_entries"]
                  ) );

        return DB::insertId();
    }
    
    
    function addEntry() {
        
    }

}
