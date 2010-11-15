<?
if (strstr($_POST['recording'],"/")) exit;
if (strstr($_POST['stream'],"/")) exit;
if (strstr($_POST['recording'],"..")) exit;
if (strstr($_POST['stream'],"..")) exit;
  // save file
  //$fp=fopen("components/recordings/".$_POST['recording'].".vwr","w");
  $fp=fopen("components/recordings/". $_POST['stream'] .".vwr","w");
  if ($fp)
  {
    fwrite($fp, $_POST['stream'].";;;".time().";;;".$_POST['rectime']);
    fclose($fp);
  }
	
  if (file_exists("components/snapshots/".$_POST['stream'].".jpg"))  
  	copy("components/snapshots/".$_POST['stream'].".jpg","components/snapshots/".$_POST['recording'].".jpg");
?>loadstatus=1