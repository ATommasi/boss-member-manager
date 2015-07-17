<?php

    class Util {
        var $c;
        function __construct($c) {
            $this->c = $c;
        }
        function redirect($location) {
            header("Location: /$location");
            exit();
        }

        function loadController($which) {
            if (file_exists("../controllers/$which.php")) {
                include_once ("../controllers/$which.php");
            } else {
                include ("../helpers/assign.php");
                $this->c["Messages"]->add("Controller $which not found");
                include ("../helpers/render.php");
            }
        }


        #// generates html code for formatting gw2 currency
        function formatgw2currency($amt) {
            $oamt = $amt;
            $cur="";

            if ($amt >= 10000  ) {
                $g = $amt / 10000;
                $amt = $amt - (floor($g) * 10000);
                $cur .= number_format(floor($g))." <img src='/images/Gold_coin.png'> ";
            }

            if ($amt  >= 100) {
                $s = $amt / 100;
                $amt = $amt - (floor($s) * 100);
                $cur .= number_format(floor($s))." <img src='/images/Silver_coin.png'> ";
            } elseif ($g > 0) {
                $cur .= "0 <img src='/images/Silver_coin.png'> ";
            }

            $c = number_format($amt);


            $cur .= number_format(floor($c))." <img src='/images/Copper_coin.png'> ";



            //$g = floor($g);
            //$s = floor($s);
            //$c = floor($c);




            $html = "<span>$cur</span>";

            return $html;

        }

    }

?>