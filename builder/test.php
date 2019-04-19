<?php
function getScannedDocsOLD() {
	$h = fopen('http://goodspeed.lib.uchicago.edu/images/', 'rb');
	$htmlstring = stream_get_contents($h);
	fclose($h);

	$dom = new DOMDocument();
	$dom->loadHTML($htmlstring);

	$xp = new DOMXPath($dom);
	$nl = $xp->query('//a/@href');

	$docs = array();
	foreach ($nl as $n) {
		if (eregi('^([0-9]{4})/$', $n->nodeValue, $m))
			$docs[] = $m[1];
	}
	return $docs;
}

function getScannedDocs() {
	$docs = array();

	$h = opendir('/projects/goodspeed/web/images');
	while (false !== ($entry = readdir($h))) {
		if (eregi('^[0-9]{4}$', $entry)) {
			$docs[] = $entry;
		}
	}
	closedir($h);

	return $docs;
}

var_dump(getScannedDocs());
?>
