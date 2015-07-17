<div class="container">
<?php
$debug = $c["Messages"]->getDebug();
if (count($debug)) {
    echo "<pre>";
    foreach ($debug as $d) {
        if (is_array($d)) {
            print_r($d);
        } else {
            echo $d."<br />";
        }

    }
    echo "</pre>";
}
?>
</div>