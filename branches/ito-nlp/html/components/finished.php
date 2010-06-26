<?
include("header.php");
?>
<p>Recording Result:
  <?=$_GET['result']?>
  <?$vid=$_GET['stream'];?>
  
  
</p>
<p><a href="recorded_videos.php">Browse Video Recordings</a></p>
<p><a href="streamplay.php?vid=<?=($vid)?>">Play video</a></p>