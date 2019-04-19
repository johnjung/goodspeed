<?php

/*
 * FUNCTIONS
 */

/*
 * Build a browse from TEI records
 * input:
 *   xpath query (e.g. '//material')
 * output:
 *   [
 *    { 'metal': [ {'doc': '0129'} ] },
 *    { 'paper': [ {'doc': '0931'}, {'doc': '0972'} ] }
 *   ]
 * (this lets me deliver JSON data that is sorted properly on the backend.)
 */

function descriptivebrowse($q, $capitalize = False) {
	$scanneddocs = getScannedDocs();
	$descriptivedocs = getDescriptiveDocs();

	$browses = array();
	foreach ($descriptivedocs as $doc) {
		$xml = new DOMDocument();
		$xml->load('../web/metadata/descriptive/' . $doc . '.xml');
	
		$xp = new DOMXPath($xml);
		$nl = $xp->query($q);

		foreach ($nl as $n) {
			$browse = cleanString($n->nodeValue, $capitalize);
			if (!array_key_exists($browse, $browses))
				$browses[$browse] = array();
			$browses[$browse][$doc] = true;
		}
	}

	$results = array();
	foreach ($browses as $browse => $docs) {
		$values = array();
		foreach (array_keys($docs) as $doc) 
			$values[] = array(
				'doc' => (string)$doc,
				'docstr' => getManuscriptTitle($doc),
				'digitized' => in_array($doc, $scanneddocs),
				'metadata' => in_array($doc, $descriptivedocs)
			);
		$results[] = array($browse => $values);
	}
	return $results;
}

/*
 * Helper functions for descriptive browse.
 * input: 
 *   utf-8 string.
 * output: 
 *   lowercase, special characters removed string. 
 */

function cleanString($s, $capitalize = False) {
  $s = htmlentities($s, ENT_COMPAT, "UTF-8");
  $s = preg_replace ('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', $s);
  $s = html_entity_decode($s);

  if ($capitalize)
    $s = ucsmart($s);

	$s = trim($s);
	$s = preg_replace('/\s\s+/', ' ', $s);

  return $s;
}

function ucsmart($text) {
  return preg_replace('/([^a-z]|^)([a-z])/e', '"$1".strtoupper("$2")', strtolower($text));
}	

/*
 * Build a browse from structural metadata (XML output from the Access table.)
 * input:
 *   q: xpath query
 * output:
 *   {
 *    'metal': [ {'doc': '0129'} ],
 *    'paper': [ {'doc': '0931'}, {'doc': '0972'} ]
 *   }
 */

function structuralbrowse($q) {
	$scanneddocs = getScannedDocs();
	$descriptivedocs = getDescriptiveDocs();

	$captions = getPageCaptions();

	$xml = new DOMDocument();
	$xml->load('../web/metadata/structural.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/dataroot/tblObjectStructuralRev');

	/* Create browses */
	$browses = array();
	foreach ($nl as $n) {
		$docnl = $xp->query('document', $n);
		$doc = $docnl->item(0)->nodeValue;

		$objnl = $xp->query('object', $n);
		$obj = $objnl->item(0)->nodeValue;	

		$subnl = $xp->query($q, $n);
		foreach ($subnl as $subn) {
			$browseterms = $subn->nodeValue;
			if (!$browseterms)
				continue;
			foreach (explode(';', $browseterms) as $browseterm) {
				$browseterm = preg_replace('/\s+/', ' ', trim($browseterm));
				if (!array_key_exists($browseterm, $browses))
					$browses[$browseterm] = array();
				if (array_key_exists($doc . $obj, $captions))
					$objstr = $captions[$doc . $obj];
				else
					$objstr = $obj;
				$browses[$browseterm][] = array(
					'doc' => $doc, 
					'docstr' => getManuscriptTitle($doc),
					'obj' => $obj,
					'objstr' => $objstr,
					'digitized' => in_array($doc, $scanneddocs),
					'metadata' => in_array($doc, $descriptivedocs)
				);
			}
		}
	}

	/* Format output so ordering can be preserved outside of PHP */
	$results = array();
	foreach ($browses as $browse => $result) {
		usort($result, 'cmpDocumentAndObject');
		$results[] = array($browse => $result);
	}

	return $results;
}

function cmpDocumentAndObject($a, $b) {
	if ($a['doc'] != $b['doc']) 
		return ($a['doc'] < $b['doc']) ? -1 : 1;
	else if ($a['obj'] != $b['obj'])
		return ($a['obj'] < $b['obj']) ? -1 : 1;
	else 
		return 0;
}

/*
 * Build a browse from gms.xml. (It's metametadata!)
 */

function metabrowse($q) {
	$scanneddocs = getScannedDocs();
	$descriptivedocs = getDescriptiveDocs();

	$xml = new DOMDocument();
	$xml->load('../web/metadata/gms.xml');
	
	$xp = new DOMXPath($xml);
	$nl = $xp->query('//msItem');

	$browses = array();
	foreach ($nl as $n) {
		$msnl = $xp->query('manuscript',$n);
		$ms = $msnl->item(0)->nodeValue;
		$ms = str_replace('Ms. ', '', $ms);
		$doc = sprintf("%04d", $ms);

		if ($doc == '0000')
			continue;

		$browsetermnl = $xp->query($q, $n);
		foreach ($browsetermnl as $browsetermn) {
			$browseterm = (string)$browsetermn->nodeValue;
			if (!array_key_exists($browseterm, $browses))
				$browses[$browseterm] = array();
			$browses[$browseterm][$doc] = true;
		}
	}

	$results = array();
	foreach ($browses as $browse => $docs) {
		$values = array();
		foreach (array_keys($docs) as $doc) 
			$values[] = array(
				'doc' => (string)$doc,
				'docstr' => getManuscriptTitle($doc),
				'digitized' => in_array($doc, $scanneddocs),
				'metadata' => in_array($doc, $descriptivedocs)
			);
		$results[] = array($browse => $values);
	}
	return $results;
}

/*
 * comparison functions for sorting.
 */

function alphaCmp($a, $b) {
	$a = array_keys($a);
	$a = $a[0];
	$b = array_keys($b);
	$b = $b[0];
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

function canonCmp($a, $b) {
	$a = array_keys($a);
	$a = indexOf($a[0]);
	$b = array_keys($b);
	$b = indexOf($b[0]);
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

/*
 * helper function for canonCmp 
 */

function indexOf($book) {
	$books = array( "Genesis", "Exodus", "Leviticus", "Numbers",
	"Deuteronomy", "Joshua", "Judges", "Ruth", "1 Samuel", "2 Samuel", "1 Kings",
	"2 Kings", "1 Chronicles", "2 Chronicles", "Ezra", "Nehemiah", "Esther", "Job",
	"Psalms", "Proverbs", "Ecclesiastes", "Song of Solomon", "Isaiah", "Jeremiah",
	"Lamentations", "Ezekiel", "Daniel", "Hosea", "Joel", "Amos", "Obadiah",
	"Jonah", "Micah", "Nahum", "Habakkuk", "Zephaniah", "Haggai", "Zechariah",
	"Malachi", "Matthew", "Mark", "Luke", "John", "Acts", "Romans", "1
	Corinthians", "2 Corinthians", "Galatians", "Ephesians", "Philippians",
	"Colossians", "1 Thessalonians", "2 Thessalonians", "1 Timothy", "2 Timothy",
	"Titus", "Philemon", "Hebrews", "James", "1 Peter", "2 Peter", "1 John", "2
	John", "3 John", "Jude", "Revelation" );

        for ($b=0; $b<count($books); $b++) {
                if ($books[$b] == $book) 
			break;
        }
        return $b;
}

function dateCmp($a, $b) {
	$a = array_keys($a);
	$a = getSortDate((string)$a[0]);
	$b = array_keys($b);
	$b = getSortDate((string)$b[0]);
	if ($a == $b) {
		return 0;
	}
	return ($a < $b) ? -1 : 1;
}

/* 
 * helper functions for cmpDate
 */

function getSortDate($s) {
	if (stristr($s, 'century') !== False) 
		return sprintf("%04d", ((int)stringDigits($s) - 1) * 100);
	else 
		return firstFourDigits($s);
}

function stringDigits($s) {
	$d = '';
	$i = 0;
	while ($i < strlen($s)) {
		if (ctype_digit($s[$i]))
			$d .= $s[$i];
		$i++;
	}
	return $d;
}

function firstFourDigits($s) {
	$d = '';
	$i = 0;
	while ($i < strlen($s)) {
		if (ctype_digit($s[$i]))
			$d .= $s[$i];
		else 
			$d = '';
		if (strlen($d) > 3)
			return $d;
		$i++;
	}
	return '0000';
}

/*
 * Get the 'title' of a given manuscript.
 * The title is formed from a few different elements in the gms.xml file.
 */

function getManuscriptTitle($doc) {
	$xml = new DOMDocument();
	$xml->load('../web/metadata/gms.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/ManuscriptList/msItem');

	foreach ($nl as $n) {
		$snl = $xp->query('manuscript', $n);
		if (!$snl->length)
			continue;
		$manuscript = $snl->item(0)->nodeValue;

		$currentdoc = trim($snl->item(0)->nodeValue, 'Ms. 0(OIM)');
		if ($currentdoc != trim($doc, 'Ms. 0(OIM)'))
			continue;

		$date = '';
		$snl = $xp->query('date_of_origin', $n);
		if ($snl->length)
			$date = $snl->item(0)->nodeValue;

		$language = '';
		$snl = $xp->query('language', $n);
		if ($snl->length)
			$language = $snl->item(0)->nodeValue;

		return sprintf("%s: %s %s", $manuscript, $date, $language);
	}
	return '';
}

function getPageCaptions() {
	$xml = new DOMDocument();
	$xml->load('../web/metadata/structural.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/dataroot/tblObjectStructuralRev');

	$records = array();	
	foreach ($nl as $n) {
		$snl = $xp->query('document', $n);
		$document = $snl->item(0)->nodeValue;

		$snl = $xp->query('object', $n);
		$object = $snl->item(0)->nodeValue;
			
		foreach (array('pagination1', 'pagination2', 'foliation1', 'folation2') as $k) {
			$snl = $xp->query($k, $n);
			if ($snl->length) {
				$v = $snl->item(0)->nodeValue;
				if ($v) {
					$records[$document . $object] = $v;
					continue 2;
				}
			}
		}
	}
	return $records;
}

/*
 * Check to see if a given manuscript has been digitized.
 */

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

function getScannedDocsOld() {
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

function getDescriptiveDocs() {
	$docs = array();
	$h = opendir(dirname(__FILE__) . '/../web/metadata/descriptive');
	while (($f = readdir($h)) !== false) {
		if ($f == '.' || $f == '..')
			continue;
		$n = explode('.', $f);
		if (!ctype_digit($n[0]) || $n[1] != 'xml')
			continue;
		$docs[] = $n[0];
	}   
	return $docs;
}

/*
 * MAIN 
 */

if ($argc < 2)
	die();

switch ($argv[1]) {
	case 'browsetitle':
		$results = metabrowse('title');
		usort($results, 'alphaCmp');
		break;
	case 'browsecommonname':
		$results = metabrowse('other_name');
		usort($results, 'alphaCmp');
		break;
	case 'browselocation':
		$results = descriptivebrowse('//name[@type="place"]/@reg');
		usort($results, 'alphaCmp');
		break;
	case 'browsedate':
		$results = metabrowse('date_of_origin');
		usort($results, 'dateCmp');
		break;
	case 'browselanguage':
		$results = metabrowse('language');
		usort($results, 'alphaCmp');
		break;
	case 'browsepersonalname':
		$results = descriptivebrowse('//name[@type="person"]/@reg|//name[@type="org"]/@reg');
		usort($results, 'alphaCmp');
		break;
	case 'browsematerial':
		$results = descriptivebrowse('//material', True);
		usort($results, 'alphaCmp');
		break;
	case 'browseminiature':
		$results = structuralbrowse('decoMins1 | decoMins2');
		usort($results, 'alphaCmp');
		break;
	case 'browseminiaturekeyword':
		$results = structuralbrowse('decoMinsKywds1|decoMinsKywds2');
		usort($results, 'alphaCmp');
		break;
	case 'browsebiblebks':
		$results = structuralbrowse('bibleBks1 | bibleBks2');
		usort($results, 'canonCmp');
		break;
}

print(json_encode($results));

?>
