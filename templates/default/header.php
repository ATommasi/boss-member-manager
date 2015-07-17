
<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/custom.css">
   <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
   <!--<link rel="stylesheet" href="css/jqueryui/themes/vader/jquery-ui.min.css">
   <link rel="stylesheet" href="css/jqueryui/themes/vader/theme.css">-->

   <!-- jquery stuff -->
   <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
   <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

   <script src="//cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js"></script>
   <script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>


   <!-- bootstrap -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script type='text/javascript' src="http://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>

   <script src="js/main.js"></script>

   <title>Fatal Aggression Guild Management</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">BOSS Guild Manager</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">

            <li class="<?=($c["currentPage"] == "users" ? "active": "");?>"><a href="/users">Members</a></li>
            <li class="<?=($c["currentPage"] == "applications" ? "active": "");?>"><a href="/applications">Applications</a></li>
            <li class="<?=($c["currentPage"] == "raffle" ? "active": "");?>"><a href="/raffle">Raffle Management</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li class="divider"></li>
                </ul>
            </li>
        </ul>
        <div class="col-sm-3 col-md-3 pull-right">
 <?php
if ($c["User"]->isLoggedIn()) {
   echo "<div class='navbar-text navbar-right'>Logged in as ".$c["User"]->username."
   [<a href='/login?action=logout'>logout</a>]<br />";
} else {
   echo "<div class='navbar-text navbar-right'><br />";
}


?>
        </div>
    </div>
</nav>

<div class="container">