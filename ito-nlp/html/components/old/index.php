<?
include("header.php");
?>
<div style="padding:20px">
	<form id="form1" name="form1" method="post" action="components/videorecorder.html">
		<p><b>A. RECORD VIDEO</b></p>
		<p>
			<b>Provide a Label for your Video Recording Channel</b>
			<span>Channel Name / Username</span>
			<input name="username" type="text" id="username" value="Channel" size="12" maxlength="12" />
			<input type="submit" name="button" id="button" value="Publish Channel" />
		</p>
		<p><b>B. BROWSE RECORDINGS</b></p>
		<p><a href="recorded_videos.php">Video Recordings</a></p>
	</form>
</div>
<div class="info">
	<p><b>Suggestions</b></p>
	<p>For  best experience with these applications we recommend updating to latest flash player: <a href="http://get.adobe.com/flashplayer/" target="_blank">http://get.adobe.com/flashplayer/</a>.
		<br/>
		When the video recording application starts, flash will ask you if you want to start streaming your camera and microphone. Allow flash to send your stream and select the right video and audio devices you want to use. </p>
	<p>There are 2 ways to select hardware devices/drivers you'll use for broadcasting:
		<br/>
		A. Click inside webcam preview panel and a settings panel will extend it. Click camera or microphone to select.
		<br/>
		B. Right click Flash &gt; Settings... and browse to the webcam/microphone minitabs. 
	</p>
</div>
</body>
