<?php

$h = opendir('/projects/goodspeed/web/images/thumbs');
while (false !== ($entry = readdir($h))) {
	if (eregi('^gms-([0-9]{4})-[0-9]{3}\.jpg$', $entry, $m)) {
		$docs[$m[1]] = true;
	}
}
closedir($h);
$docs = array_keys($docs);
sort($docs);

/*
 * PHP weirdly tries very hard to give back integer keys in arrays.
 * force all the doc numbers to strings before outputting.
 */

$stringdocs = array();
foreach ($docs as $doc) 
	$stringdocs[] = (string)$doc;
print(json_encode($stringdocs));
?>
