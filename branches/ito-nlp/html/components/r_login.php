<?php
include("inc.php");
if (isset($_COOKIE["usname"])) $username=$_COOKIE["usname"];
$username=substr($username,0,32);
$msg="";
if (!$username) $msg="No recording name provided!";

$recordingId="-".base_convert(time(),10,36);

$layoutCode=<<<layoutEND
id=0&label=Video&x=346&y=10&width=326&height=298; id=1&label=Camcorder&x=10&y=10&width=326&height=298
layoutEND;

?>
server=<?=$rtmp_server?>
&serverAMF=<?=$rtmp_amf?>
&username=<?=$username?>
&recordingId=<?=$recordingId?>
&msg=<?=$msg?>
&loggedin=1&camWidth=320&camHeight=240&camFPS=15&camBandwidth=49158&showCamSettings=1&camMaxBandwidth=131072&advancedCamSettings=1&recordLimit=600&bufferLive=900&bufferFull=900&bufferLivePlayback=0.2&bufferFullPlayback=10&layoutCode=<?=urlencode($layoutCode)?>
&fillWindow=0&loadstatus=1