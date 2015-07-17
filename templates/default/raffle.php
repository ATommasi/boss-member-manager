
<script src="/js/typeahead.bundle.js"></script>
<script type="text/javascript" src="/js/jquery.gw2tooltip.js"></script>
<script type="text/javascript" src="/js/raffle.js"></script>

<?php

  $currentRaffleID = $_REQUEST["raffle_id"];

  if (isset($_REQUEST["action"])) {

    if ($_REQUEST["action"] == "newraffle") {
      $currentRaffleID = $raffle->addRaffle(array(
                "start_date" => $_POST["start_date"],
                "end_date" => $_POST["end_date"],
                "max_entries" => $_POST["max_entries"],
                "entry_fee" => $_POST["entry_fee"]
      ));
    }


  }

  if (empty($currentRaffleID)) $currentRaffleID = $raffle->getCurrentRaffle();

  $raffle->setCurrentRaffle($currentRaffleID);





    $allRaffles = $raffle->getRaffles();
?>

<input type="hidden" name="raffle_id" id="raffle_id" value="<?=$currentRaffleID?>" />

<div class="row">
<div class="container col-md-6">
  <table id='rafflelist' class="center-block table table-striped table-hover">
  <thead>
    <th></th>
    <th>ID</th>
    <th>Start Date</th>
    <th>End Date</th>
    <th>Entries</th>
    <th>Prize Value</th>
  </thead>
  <tbody>

  <?php foreach ($allRaffles as $r) {
    unset($class);
  
    if ($r["raffle_id"] == $currentRaffleID) {
      $class="info";
      
    }
    echo "<tr class='$class'>
      <td>
       <div class='raffleaction dropdown btn-group invisible'>
            <button class=btn-link dropdown-toggle' type='link' id='dropdownMenu2' data-toggle='dropdown' aria-expanded='true'>
                    <a href='#' class='dropdown-toggle'><img src='/images/cog.png' width='12'></a>
          </button>
            
            <ul class='dropdown-menu' role='menu' aria-labelledby='menu_'>
              <li role='presentation' class='dropdown-header'>Action</li>
              <li role='presentation' raffle_id='".$r["raffle_id"]."' class='raffle_upd' rel='edit'><a href='#'  class='showmodal'>Edit</a></li>
              <li role='presentation' raffle_id='".$r["raffle_id"]."' class='raffle_upd' rel='del'><a href='#'  class='showmodal'>Delete</a></li>
            </ul>
        </div>     
      
      </td>
      <td>".$r["raffle_id"]."</td>
      <td>".date("m/d/Y", strtotime($r["start_date"]))."</td>
      <td>".date("m/d/Y", strtotime($r["end_date"]))."</td>
      <td>".$raffle->getTotalEntries($r["raffle_id"])."</td>
      <td>".$c["Util"]->formatgw2currency($raffle->getPrizeValue($r["raffle_id"]))."</td>
    </tr>";
  }
    ?>
  </tbody>
</table>
</div>

  <div class='col-md-6'>
  <a class="btn btn-default" data-toggle="collapse" href="#raffleSettings" aria-expanded="false" aria-controls="raffleSettings">
  Add new raffle
</a>

<div class="collapse" id="raffleSettings">
  <div class="well">


  <form class="form-horizontal" action="?action=newraffle" method="post">

  <div class="form-group">
    <label class="col-sm-2 control-label">Raffle Date</label>
    <div class="col-sm-3">
      <input type="date" name="start_date" data-date-clear-btn=false class="form-control input-sm" id="iend" value="<?=date("Y-m-d")?>">
    </div>

    <label for="iend" class="col-sm-1 control-label">To</label>
    <div class="col-sm-3">
        <input type="date" name="end_date" data-date-clear-btn=false class="form-control input-sm" id="iend" value="<?=date("Y-m-d", strtotime("2 weeks"))?>">
    </div>
  </div>

  <div class="form-group">
    <label for="gold" class="col-sm-2 control-label">Entry Fee</label>
    <div class="col-xs-2">
      <input type="number" name="entry_fee" class="form-control input-sm" id="gold" placeholder="" value="1">
    </div>

    <label for="maxEntry" class="col-sm-2 control-label">Max Entries</label>
    <div class="col-xs-2">
      <input type="number" name="max_entries" class="form-control input-sm" id="maxEntry" placeholder="" value="1">
    </div>

    <div class="col-sm-offset-2 col-sm-2">
      <button type="submit" class="btn btn-default">Save</button>
    </div>
  </div>
</form>


  </div>
</div>
</div>
</div>

<div class="row">

<?php
  $entries = $raffle->getEntries();
?>


  <div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Current Entries</h3>
  </div>
  <div class="panel-body">
    <div class="row">
      <div class='col-sm-6'><label>Total Members:</label> <span id="total_entries"><?=$raffle->getTotalEntries()?></span></div>
      <div class='col-sm-6'><label>Gold Collected:</label> <span id="total_gold"><?=$raffle->getGoldCollected()?></span><img src="/images/Gold_coin.png" ></div>
    </div>

    <div class="row">
      <div class='col-sm-6'>
       <a data-toggle="collapse" href="#addentrant" aria-expanded="false" aria-controls="addentrant">Add Entrant</a>
      </div>
    </div>
    <div class="row">
<div class="collapse" id="addentrant">
      <div class='well'>

<div class='row'>
      <form id='add_entry' method="get">
    
    
    <input type="hidden" name="entry_userid" id="entry_user_id" value="" />
    <input type="hidden" name="entry_username" id="entry_username" value="" />
    <input type="hidden" name="entry_account_id" id="entry_account_id" value="" />
    
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <div class="col-xs-4">
    <label for="user_search">Name</label>
    <input type="text" autocomplete="off" data-provide="typeahead" class="form-control input-sm user_typeahead" id="gw2item_typeahead" placeholder="Search Users">
  </div>
  <div class="col-xs-2">
    <label for="exampleInputEmail2">Amount:</label>
    <input type="number" class="form-control input-sm" min="1" step="1" id="entry_amt" placeholder="" value="">
  </div>

  <div class="col-xs-2">
    <label for="add_btn"></label>
    <input type="submit" class="btn btn-default" id="add_entry_submit">
  </div>
  </form>
</div>
</div>
      </div>
    </div>
  </div>
    <ol class="list-group" id='raffle_entries'>
<?php
  if (count($entries)) {
    foreach ($entries as $e) {
      echo "<li class='list-group-item'><a href='#' entry_amt='".$e["entry_amount"]."' user_id='".$e["user_id"]."' class='rmv_entry glyphicon glyphicon-remove'></a>&nbsp;".$e["username"];
      echo "<span class='user_entry_amt pull-right'>".$e["entry_amount"]."<img src='/images/Gold_coin.png' ></span>";
      echo "</li>";
    }
  } else {
    echo "<li class='list-group-item'>No entries found</li>";
  }
?>
    </ol>
</div>
</div> <!--col-->



<?php
  $prizes = $raffle->getPrizes();
?>

   <div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Prize List</h3>
  </div>
  <div class="panel-body">
    <form>
    <input type="text" autocomplete="off" data-provide="typeahead" class="form-control input-sm item_typeahead" id="gw2item_typeahead" placeholder="Add New Item">
    
    </form>
  </div>
  <ul  class="list-group" id="prizeitems">

    <?php
  if (count($prizes)) {
    foreach ($prizes as $p) {
      echo "<li class='list-group-item'><a href='#' prize_id='".$p["prize_id"]."' class='rmv_prize glyphicon glyphicon-remove'></a>&nbsp;<img width='32' src='".$p["icon"]."'>".$p["name"]."  (".$p["item_type"].")  <div class='pull-right'>".$c["Util"]->formatgw2currency($p["tp_price"])."</div></li>";
    }
  } else {
    echo "<li class='list-group-item'>No prizes found</li>";
  }
?>


</div>
</div> <!--col-->



</div> <!-- row-->


</div> <!--container--><div class="container">
</div></div>



</body>
