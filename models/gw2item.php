<?php
include("../helpers/assign.php");
require_once ("../helpers/gw2api/Service.php");

class gw2item extends PhpGw2Api\Service {

    private $c;

    function __construct($c) {
        $this->c = $c;

        parent::__construct($this->c["cacheDIR"], $this->c["cacheTTL"]);


    }
}