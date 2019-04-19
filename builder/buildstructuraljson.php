<?php

/*
 * build json data describing each document. 
 * save this to build thumbnails, etc. 
 */

if ($argc < 2)
	die();

$xml = new DOMDocument();
$xml->load('../web/metadata/structural.xml');

$xp = new DOMXPath($xml);
$q = sprintf('/dataroot/tblObjectStructuralRev[document="%s"]', trim($argv[1]));

$nl = $xp->query($q);

$records = array();	
foreach ($nl as $n) {
	$snl = $xp->query('object', $n);
	$object = $snl->item(0)->nodeValue;

	$caption = array();
	$snl = $xp->query('foliation1|foliation2|pagination1|pagination2|physFt', $n);
	foreach ($snl as $sn) {
		if ($sn->nodeValue)
			$caption[] = $sn->nodeValue;
		if (count($caption) == 2)
			break;
	}

	if (count($caption) == 1)
		$caption = $caption[0];
	else if (count($caption) == 2)
		$caption = sprintf("%s: %s", $caption[0], $caption[1]);
	
	$records[] = array('document' => trim($argv[1]), 'object' => $object, 'caption' => $caption);
}

/* sort records */
function cmpObjects($a, $b) {
	if ($a['object'] == $b['object']) {
		return 0;
	}
	return ((int)$a['object'] < (int)$b['object']) ? -1 : 1;
}
usort($records, 'cmpObjects');

print json_encode($records);

?>
