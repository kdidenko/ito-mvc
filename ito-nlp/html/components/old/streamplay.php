<?php
include("header.php");
include("inc.php");
$vid = $mvc->getParam('vid');
$swfurl="components/streamplayer.swf?streamName=" . urlencode($vid) . 
		"&serverRTMP=".urlencode($rtmp_server) . 
		"&templateURL=";
?>

<div align="center" style="width:320px;height:240px" class="info">

<object width="100%" height="100%">
      <param name="movie" value="<?=$swfurl?>"></param>
      <param name="scale" value="noscale" />
      <param name="salign" value="lt"></param>
      <param name="allowFullScreen" value="true"></param>
      <param name="allowscriptaccess" value="always"></param>
      <embed 
      		width="100%" 
      		height="100%" 
      		scale="noscale" 
      		salign="lt" 
      		src="<?=$swfurl?>" 
      		type="application/x-shockwave-flash" 
      		allowscriptaccess="always" 
      		allowfullscreen="true">
      	</embed>
 </object>
 <p><a href="recorded_videos.php"> Back to Recordings List</a></p>

</div>