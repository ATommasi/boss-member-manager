<?php
//require_once('../helpers/assign.php');
require_once('../models/meekrodb.class.php');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$db = new MeekroDB();

$items = json_decode(file_get_contents('https://api.guildwars2.com/v2/items/'));




$total = $items[count($items)];

$start = 1;
$end = 200;

foreach ($items as $i) {
    $ib[] = $i;

    if (count($ib) >= 190) {
        $batchList[] = implode(",", $ib);
        unset($ib);
    }
}
$batchList[] = implode(",", $ib);

foreach ($batchList as $ItemRequest) {
    $itemDetails = json_decode(file_get_contents('https://api.guildwars2.com/v2/items?ids='.$ItemRequest));

    foreach ($itemDetails as $item) {

        DB::replace('gw2_items', array(
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'item_type' => $item->type,
            'rarity' => $item->rarity,
            'vendor_value' =>$item->vendor_value,
            'icon' =>$item->icon
        ));
    }


    $price = json_decode(file_get_contents('https://api.guildwars2.com/v2/commerce/prices?ids='.$ItemRequest));

    #// get tp prices
    foreach ($price as $tp) {

        $sell_price = $tp->sells->unit_price;

        DB::update('gw2_items', array(
               'tp_price' => $sell_price
        ), "id=%i", $tp->id);
    }
}
