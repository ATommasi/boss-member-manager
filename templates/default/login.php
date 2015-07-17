
<?php
   if ($loginFailed)
            echo "<div class='alert alert-danger alert-dismissible' role='alert'>Could not login, check username/password</div> ";
?>

   <div class='row'>
   <div class='col-md-12'><h3>Please Login</h3>
   <div class='row'>
   <div class='col-sm-2 col-md-1'> <img src='http://files.enjin.com.s3.amazonaws.com/902615/modules/menu/28738434-full_48.png' /></div>
   
   <div class='col-sm-6 col-md-11'>
   
   <form class="form-horizontal" action="/login" method="post">
       <input type="hidden" name="action" value="login">
    <div class="form-group">
       <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
       <div class="col-sm-3">
       <input type="email" class="form-control" id="inputEmail3" name="email" placeholder="Email">
       </div>
       </div>
       <div class="form-group">
       <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
       <div class="col-sm-3">
       <input type="password" class="form-control" name="password" id="inputPassword3" placeholder="Password">
       </div>
       </div>
       <div class="form-group">
       <div class="col-sm-offset-2 col-sm-3">
       <button type="submit" class="btn btn-default">Sign in</button>
       </div>
       </div>
    </form>';


    </div>
    </div>
