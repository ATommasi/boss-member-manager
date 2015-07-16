<?php
// load framework files
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
// connect to local server, authenticate and spawn an object for the virtual server on port 9987
$ts3_VirtualServer = TeamSpeak3::factory("serverquery://serveradmin:648925@ts3.fatalaggressionboss.com:10011/?server_port=9987");
// build and display HTML treeview using custom image paths (remote icons will be embedded using data URI sheme)
#echo $ts3_VirtualServer->getViewer(new TeamSpeak3_Viewer_Html("images/icons/", "images/flags/", "data:image"));
echo $ts3_VirtualServer->getViewer(new TeamSpeak3_Viewer_Html());
?>
