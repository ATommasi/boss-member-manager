
<?php
$enjin = $c["enjin"];
$sessionFile = "../config/enjinSession";

$appID = $_REQUEST["app_id"];

$sessionID = trim(file_get_contents($sessionFile));

$app = $enjin->execute('Applications.getApplication', array('session_id'=>$sessionID, 'application_id'=>$appID));



?>

<div class="row">
    <div class="col-md-3"><strong>Application ID</strong></div>
    <div class="col-md-3"><strong><?=$app["application_id"]?></strong></div>
</div>
<div class="row">
    <div class="col-md-3"><strong>Username</strong></div>
    <div class="col-md-3"><strong><?=$app["username"]?></strong></div>
</div>


<?php
echo "<pre>"; print_r($app); echo "</pre>";
?>