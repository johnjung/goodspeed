<?php

$docs = array();
$mtime = array();

$h = opendir('/projects/goodspeed/web/images');
while (false !== ($entry = readdir($h))) {
	if (eregi('^[0-9]{4}$', $entry)) {
		$docs[] = $entry;
		$mtime[] = filemtime(sprintf("/projects/goodspeed/web/images/%s", $entry));
	}
}
array_multisort($mtime, $docs);
$docs = array_reverse($docs);
$mtime = array_reverse($mtime);

print json_encode(date("F j, Y", $mtime[0]));

?>
