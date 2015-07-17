<?php

$messages = $c["Messages"]->getAll();

foreach ($messages as $type=>$msg) {
    echo "<div class='alert alert-$type' role='alert'>";
    foreach ($msg as $m) {
        echo "$m<br />";
    }
    echo "</div>";
}