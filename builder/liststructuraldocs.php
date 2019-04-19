<?php

$xml = new DOMDocument();
$xml->load('../web/metadata/structural.xml');

$xp = new DOMXPath($xml);
$q = '/dataroot/tblObjectStructuralRev/document';
$nl = $xp->query($q);

$docs = array();	
foreach ($nl as $n) {
	if ($n->nodeValue)
		$docs[$n->nodeValue] = true;
}
printf("%s\n", implode(' ', array_keys($docs)));
?>
