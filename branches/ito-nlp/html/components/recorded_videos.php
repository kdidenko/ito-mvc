<?
include ("header.php");
?>
<div class="info"><b>Video Recordings</b> / <a href="index.php">Record
New Video</a><br />

<?php
$dir = "components/recordings";
$handle = opendir ( $dir );
while ( ($file = readdir ( $handle )) !== false )
	if ((substr ( $file, - 3 ) == "flv") && (! is_dir ( "$dir/" . $file ))) {
		$vid = substr ( $file, 0, - 4 );
		$params = explode ( ";;;", implode ( file ( "$dir/" . $file ) ) );
		if (count ( $params )) {
			$ts = $params [2];
			$tm = floor ( $ts / 60 );
			$ts = $params [2] - $tm * 60;
			$info = $params [0] . " ($tm:$ts)";
		}
		echo "<a href='streamplay.php?vid=$vid'><IMG WIDTH='240' TITLE='$info' ALT='$info' BORDER=5 SRC='" . (file_exists ( "snapshots/$vid.jpg" ) ? "snapshots/$vid.jpg" : "snapshots/no_video.png") . "'></a> ";
	}
?>
</div>