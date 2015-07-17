
<div class="container center-block text-center">
<div id = "rank_selecters" class="btn-group" role="group" aria-label="Ranks">
  <!--<button type='button' etagid='all' class='tagSelect btn btn-default'>All Members</button>-->

<?php
#--echo "<pre>"; print_r($data["ranks"]);
foreach ($data["ranks"] as $rank) {
  $btn =  ($rank["tagname"] == "Member" ? "primary" : "default");
 echo "<button type='button' etagid='".$rank["tag_id"]."' class='btn btn-$btn  tagSelect'>".$rank["tagname"]."</button>";
}
?>
</div>
</div>

<div class="container">


<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading">Member Management</div>
  <div class="panel-body">
    <form class="form-inline">
 Only show members who joined at least <select id='mindays' class="form-control"><option>0</option><option selected>30</option><option>60</option><option>90</option></select>Days ago
 </form>
  </div>



<table id='userTable' class='table table-striped table-hover'>
<thead>
 <tr><th></th></th><th>Account ID</th><th>Username</th><th>Join Date</th><th>Days since join</th><th></th></tr>
</thead>
<tbody></tbody>
</table>
</div>
</div>




<!-- popup account dialog -->
<div class="modal fade" id="accounteditpopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter Account ID</h4>
      </div>
      <div class="modal-body">
      <div id="accountid_form">
      <form>
        <label for="name">Account ID</label>
        <input type="text" name="accountid" id="accountid" class="text ui-widget-content ui-corner-all" />
        <input type="hidden" name="user_id" />
        <input type="hidden" name="rowid" />
      </form>
    </div>
      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_accountid">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="mynewdialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

      </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
