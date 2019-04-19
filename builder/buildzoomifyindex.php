<?php

/*
 * get the list in the order it was scanned. If I need the documents in numerical order
 * I can sort them separately.
 * descending, last modified. 
 */

$docs = array();
$mtime = array();

$h = opendir('/projects/goodspeed/web/images/thumbs');
while (false !== ($entry = readdir($h))) {
	if (eregi('^gms-([0-9]{4})-001\.jpg$', $entry, $m)) {
		$docs[] = $m[1];
		$mtime[] = filemtime(sprintf("/projects/goodspeed/web/images/thumbs/%s", $entry));
	}
}
array_multisort($mtime, $docs);
$docs = array_reverse($docs);

/*
 * PHP weirdly tries very hard to give back integer keys in arrays.
 * force all the doc numbers to strings before outputting.
 */

$stringdocs = array();
foreach ($docs as $doc) 
	$stringdocs[] = (string)$doc;
print(json_encode($stringdocs));

?>
