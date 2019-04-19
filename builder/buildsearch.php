<?php

/*
 * Be sure when we index a search, we never use an attribute as the xpath.
 * searches should always be on text that can show up.
 */

/*
 * input:
 * an xpath describing what nodes to index.
 * 
 * output:
 * Only record the first snippet from each document.
 * Add a count of the number of times each snippet occurs?
 * 
 * {
 *  'TOKEN': {
 *   'DOC': [count, 'snippet...'],
 *   'DOC': [count, 'snippet...'] },
 *  'TOKEN': {
 *   'DOC': [count, 'snippet...'],
 *   'DOC': [count, 'snippet...'] }}
 */
function descriptiveindex($q) {
	$charlist = " \n\t";
	$descriptivedocs = getDescriptiveDocs();

	$index = array();
	/* for each document */
	foreach ($descriptivedocs as $doc) {
		$xml = new DOMDocument();
		$xml->load('../web/metadata/descriptive/' . $doc . '.xml');
	
		$xp = new DOMXPath($xml);
		$nl = $xp->query($q);

		/* for each node */
		foreach ($nl as $n) {
			/* build an array of 'words'. A word is a space separated piece
			 * of the value of a node. It includes things like punctuation,
			 * captitalization, etc. 
			 */
			$words = array();
			$word = strtok($n->nodeValue, $charlist);
			while ($word !== false) {
				$words[] = $word;
    				$word = strtok($charlist);
			}

			foreach ($words as $word) {	
				if (tokenize($word) == '')
					continue;

				/* add an entry to index. Update the count. Only add the first
 				 * snippet from the document. */
				if (!array_key_exists(tokenize($word), $index)) 
					$index[tokenize($word)] = array();
				if (!array_key_exists($doc, $index[tokenize($word)]))
					$index[tokenize($word)][$doc] = array(1, descriptivesnippet($n, $word));
				else
					$index[tokenize($word)][$doc][0]++;
			}
		}
	}
	return $index;
}

function metaindex($q) {
	$charlist = " \n\t";

	$xml = new DOMDocument();
	$xml->load('../web/metadata/gms.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/ManuscriptList/msItem');

	$index = array();

	/* step through msItem nodes */
	foreach ($nl as $n) {
		/* get doc */
		$snl = $xp->query('manuscript', $n);
		// strip out 'Ms.' and '(OIM)'
		$doc = str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue);
		// add leading zeros
		$doc = sprintf("%04d", $doc);

		/* step through result nodes (probably only one each) */
		$snl = $xp->query($q, $n);

		foreach ($snl as $sn) {
			/* build word array */
			$words = array();
			$word = strtok($sn->nodeValue, $charlist);
			while ($word !== false) {
				$words[] = $word;
    				$word = strtok($charlist);
			}

			/* step through words */
			foreach ($words as $word) {
				if (tokenize($word) == '')
					continue;

				/* add an entry to index. Update the count. Only add the first
 				 * snippet from the document. */
				if (!array_key_exists(tokenize($word), $index)) 
					$index[tokenize($word)] = array();
				if (!array_key_exists($doc, $index[tokenize($word)]))
					$index[tokenize($word)][$doc] = array(1, metasnippet($doc));
				else
					$index[tokenize($word)][$doc][0]++;
			}

		}
	}
	return $index;
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

function getThumbnailList($sort = False) {
	$thumbs = array();

	$h = opendir('/projects/goodspeed/web/images/thumbs');
	while (false !== ($entry = readdir($h))) {
		$chunks = explode('-', $entry);
		if (count($chunks) < 3)
			continue;
		$thumbs[] = $chunks[1] . $chunks[2];
	}
	return $thumbs;
}

function getThumbnailListOld($sort = False) {
	$h = fopen('http://goodspeed.lib.uchicago.edu/images/thumbs', 'rb');
	$htmlstring = stream_get_contents($h);
	fclose($h);

	$dom = new DOMDocument();
	$dom->loadHTML($htmlstring);

	$xp = new DOMXPath($dom);
	$nl = $xp->query('//a/@href');
	$thumbs = array();
	foreach ($nl as $n) {
		$pathparts = pathinfo($n->nodeValue);
		$filename = $pathparts['filename'];
		$chunks = explode('-', $filename);
		if (count($chunks) < 3)
			continue;
		$thumbs[] = $chunks[1] . $chunks[2];
	}
	return $thumbs;
}

function structuralindex($q) {
	$charlist = " \n\t";

	$captions = getPageCaptions();
	$scannedthumbs = getThumbnailList();

	$xml = new DOMDocument();
	$xml->load('../web/metadata/structural.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/dataroot/tblObjectStructuralRev');

	$index = array();
	foreach ($nl as $n) {
		/* get doc */
		$snl = $xp->query('document', $n);
		$doc = $snl->item(0)->nodeValue;
		/* get obj */
		$snl = $xp->query('object', $n);
		$obj = $snl->item(0)->nodeValue;
		/* actually get the relevant data now */
		$snl = $xp->query($q, $n);
		foreach ($snl as $sn) {
			/* build word array */
			$words = array();
			$word = strtok($sn->nodeValue, $charlist);
			while ($word !== false) {
				$words[] = $word;
    				$word = strtok($charlist);
			}

			/* step through words */
			foreach ($words as $word) {
				if (tokenize($word) == '')
					continue;

				if (!array_key_exists(tokenize($word), $index)) 
					$index[tokenize($word)] = array();
				if (!array_key_exists($doc, $index[tokenize($word)]))
					$index[tokenize($word)][$doc] = array();
				$index[tokenize($word)][$doc][] = $obj;
			}
		}
	}

	/* make this index style match the others */
	foreach ($index as $token => $docs) {
		foreach ($docs as $doc => $objs) {
			$snippet = array();
			foreach ($objs as $obj) 
				$snippet[$obj] = true;
			$snippet = array_keys($snippet);
			sort($snippet);

			$currentcaptions = array();
			$thumbsexist = array();
			foreach ($snippet as $obj) {
				if (array_key_exists($doc . $obj, $captions))
					$currentcaptions[] = $captions[$doc . $obj];
				else 
					$currentcaptions[] = $obj;
				$thumbsexist[] = in_array($doc . $obj, $scannedthumbs);
			}
			$snippet = array_map('structuralsnippet', array_fill(0, count($snippet), $doc), $snippet, $currentcaptions, $thumbsexist);
			$index[$token][$doc] = array(count($snippet), implode(' ', $snippet));
		}
	}
	return $index;
}

/* build the words that will make up the snippet. Sometimes the
 * node that contains the search is too small- in those cases, 
 * climb up the node tree to get more context for the snippet.
 */
function descriptivesnippet($node, $word) {
	// keep climbing up the dom tree until we get a decent number of words.
	do {
		$snippet = descriptivesnippetsub($node, $word);
		$node = $node->parentNode;
	} while ($node && count(explode(' ', $snippet)) < 20);

	// try to cut the snippet down one sentence at a time.
	while (true) {
		if (count(explode(' ', $snippet)) < 40)
			break;
		$try = preg_replace('/[^.<>]*$/', '', $snippet);
		$try = preg_replace('/^[^<>]*\./', '', $try);
		if ($snippet == $try)
			break;
		$snippet = $try;
	}
	return $snippet;
}

function descriptivesnippetsub($node, $word) {
	$charlist = " \n\t";

	$snippetwords = array();
	$snippetword = strtok($node->nodeValue, $charlist);
	while ($snippetword !== false) {
		if (tokenize($snippetword) == tokenize($word))
			$snippetwords[] = sprintf("<strong>%s</strong>", trim($snippetword));
		else
			$snippetwords[] = trim($snippetword);
    		$snippetword = strtok($charlist);
	}
	return implode(' ', $snippetwords);
}

/* for meta-metadata searches I used to use the following node in TEI for the 
 * snippet:
 * /TEI.2/teiHeader/fileDesc/sourceDesc/msDescription/msContents/overview
 * I changed to a summary of the gms.xml data instead, because it's more complete.
 */
function metasnippet($doc) {
	$xml = new DOMDocument();
	$xml->load('../web/metadata/gms.xml');

	$xp = new DOMXPath($xml);
	$nl = $xp->query('/ManuscriptList/msItem');
	foreach ($nl as $n) {
		$snl = $xp->query('manuscript', $n);
		$currentdoc = sprintf("%04d", str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue));
		if ($currentdoc == $doc) {
			$snippet = '';
			$snl = $xp->query('title', $n);
			$snippet .= sprintf("<strong>Title:</strong> %s<br/>", $snl->item(0)->nodeValue);
			$snl = $xp->query('other_name', $n);
			$snippet .= sprintf("<strong>Common Name:</strong> %s<br/>", $snl->item(0)->nodeValue);
			return $snippet;
		}
	}
	return '';
}

/* for structural metadata searches, figure out the snippet in the indexing function 
 * to save time. This function just converts an object number to a thumbnail- it works
 * differently than the other snippet functions. */

function structuralsnippet($doc, $obj, $caption, $thumbexists) {
	if ($thumbexists) 
		return sprintf('<div class="thumbs"><a href="../view/index.php?doc=%s&amp;obj=%s"><img height="75" src="http://goodspeed.lib.uchicago.edu/images/thumbs/gms-%s-%s.jpg"/><br/>%s</a></div>', $doc, $obj, $doc, $obj, $caption);
	else
		return sprintf('<a href="../ms/index.php?doc=%s&amp;obj=%s">%s</a>', $doc, $obj, $caption);
}

/*
 * convert a 'word', a space-separate thing in the original text,
 * into a token- something without punctuation or case.
 */
function tokenize($s) {
	$s = htmlentities($s, ENT_COMPAT, "UTF-8");
	$s = preg_replace ('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', $s);
	$s = html_entity_decode($s);

	$s = strtoupper($s);

	$s = str_replace(array("-", "_", "+", "'", "\"", "/", "[", "]", "(", ")", "!", "?", ",", ".", ":", ";"), "", $s);

	if (strlen($s) > 1)
		return $s;
	else
		return '';
}

/*
 * input:
 * {
 *  'TOKEN': {
 *   'DOC': [count, 'snippet...'],
 *   'DOC': [count, 'snippet...'] },
 *  'TOKEN': {
 *   'DOC': [count, 'snippet...'],
 *   'DOC': [count, 'snippet...'] }}
 *
 * output:
 * [
 * ['TOKEN', 'DOC', RELEVANCE, 'snippet...'],
 * ['TOKEN', 'DOC', RELEVANCE, 'snippet...']]
 */
function flattenindex($name, $index, $weight = 1.0) {
	$records = array();
	foreach ($index as $token => $docs) {
		$maxcount = 0;
		foreach ($docs as $doc => $data) {
			if ($data[0] > $maxcount) 
				$maxcount = $data[0];
		}

		foreach ($docs as $doc => $data) {
			$relevance = 1.0 - ($weight * $data[0] / $maxcount);
			$snippet = $data[1];
			$records[] = array($name, $token, $doc, $relevance, $snippet);
		}
	}
	return $records;
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

function clearindex($db, $searchtype) {
	$e = '';
	$q = sprintf("DELETE FROM search WHERE searchtype='%s';",
		sqlite_escape_string($searchtype));
	if (!($r = sqlite_exec($db, $q, $e)))
		trigger_error($e, E_USER_ERROR);
	return;
}

function loadindex($db, $records) {
	$q = '';
	foreach ($records as $record) {
		$q .= sprintf("INSERT INTO search VALUES ('%s', '%s', '%s', '%s', '%s');\n",
			sqlite_escape_string($record[0]),
			sqlite_escape_string($record[1]),
			sqlite_escape_string($record[2]),
			sqlite_escape_string($record[3]),
			sqlite_escape_string($record[4]));
	}
	$r = sqlite_exec($db, $q);
	if (!$r)
		print 'trouble';
	return;
}

function getdatabase() {
	if (!($db = sqlite_open('../web/metadata/gms', 0666, $e)))
		trigger_error($e, E_USER_ERROR);
	return $db;
}

/*
 * MAIN 
 */

$db = getdatabase();

if ($argc < 2)
	die();

clearindex($db, $argv[1]);

switch ($argv[1]) {
	case 'searchlocation':
		$index = flattenindex('searchlocation', descriptiveindex('//name[@type="place"]'), 0.96);
		break;
	case 'searchmaterial':
		$index = flattenindex('searchmaterial', descriptiveindex('//material'), 0.95);
		break;
	case 'searchpersonalname':
		$index = flattenindex('searchpersonalname', descriptiveindex('//name[@type="person"]|//name[@type="org"]'), 0.94);
		break;
	case 'searchtitle':
		$index = flattenindex('searchtitle', metaindex('title'), 0.99);
		break;
	case 'searchcommonname':
		$index = flattenindex('searchcommonname', metaindex('other_name'), 1.00);
		break;
	case 'searchdate':
		$index = flattenindex('searchdate', metaindex('date_of_origin'), 0.98);
		break;
	case 'searchlanguage':
		$index = flattenindex('searchlanguage', metaindex('language'), 0.97);
		break;
	case 'searchminiature':
		$index = flattenindex('searchminiature', structuralindex('decoMins1|decoMins2'), 0.93);
		break;
	case 'searchminiaturekeyword':
		$index = flattenindex('searchminiaturekeyword', structuralindex('decoMinsKywds1|decoMinsKywds2'), 0.92);
		break;
	case 'searchbiblebks':
		$index = flattenindex('searchbiblebks', structuralindex('bibleBks1|bibleBks2'), 0.91);
		break;
}

loadindex($db, $index);

?>
