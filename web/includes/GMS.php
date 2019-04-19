<?php

class GMS {
	
	/*
	 * get a list of manuscripts that have descriptive metadata.
 	 */	

	public $db = null;
	public $clean = null;

	function __construct($clean) {
		$this->clean = $clean;
		if (!($this->db = sqlite_open(dirname(__FILE__) .  '/../metadata/gms', 0666, $e))) {
			trigger_error($e, E_USER_ERROR);
        }
	}

	function __destruct() {
		if ($this->db) {
			sqlite_close($this->db);
            $this->db = null;
        }
	}

	function getDescriptiveDocList() {
		$docs = array();
		$h = opendir(dirname(__FILE__) . '/../metadata/descriptive');
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
	 * Once we have a few thousand thumbnail images, this can take a long time to produce. 
 	 * I precompute this and save an index of the documents that are present as json data.
	 * in testing it took .2 seconds to get this over http.
	 * bring in locally instead.
 	 */
	function getThumbsDocList() {
		return json_decode(file_get_contents(dirname(__FILE__) . '/../images/thumbs/index.json'));
	}

	/*
	 * get a list of the manuscripts that have been scanned.
	 * this works by getting apache's directory listing for the
	 * images directory, and then looking at every
	 * href in the document. if the href is for a 4 digit number
	 * directory, I assume that it's the directory these
	 * images live in.
	 */

	function getScannedDocList($sort = False) {
		return json_decode(file_get_contents(dirname(__FILE__) . '/../images/index.json'));
	}

	function getNewestScanDate() {
		return json_decode(file_get_contents(dirname(__FILE__) . '/../images/newestscandate.json'));
	}

	public function getObjectCount($doc) {
		$xml = new DOMDocument();
		if (!$xml->load('../metadata/structural.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/dataroot/tblObjectStructuralRev');
		return $nl->length;
	}

	/*
 	 * get manuscript title for a given document.
	 */

	function getManuscriptTitle($doc) {
		$xml = new DOMDocument();
		$xml->load('../metadata/gms.xml');
	
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

	/*
 	 * get a sequence of structural metadata records.
 	 * (for example, for the thumbnails page.)
 	 */

	public function getStructuralMetadata($doc, $obj, $limit) {
		// open ../metadata/json/structural/gms-dddd.js
		$xml = new DOMDocument();
		if (!$xml->load('../metadata/structural.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$q = sprintf('/dataroot/tblObjectStructuralRev[document="%s"]', $doc);
		$nl = $xp->query($q);

		$offset = ((int)$obj) - 1;

		$records = array();
		$i = 0;
		while ($i < $limit) {
			if ($i + $offset > $nl->length)
				break;
			$record = array();
			foreach (array('object' => 'object', 'physft' => 'physFt', 'folio' => 'foliation1|foliation2', 'page' => 'pagination1|pagination2') as $r => $q) {
				$snl = $xp->query($q, $nl->item($i + $offset));
				if ($snl->length)
					$record[$r] = $snl->item(0)->nodeValue;
				else
					$record[$r] = '';
			}
			$records[] = $record;
			$i++;
		}
		return $records;
	}

	/*
	 * input: 
	 *   4 digit doc number
	 * output:
	 *   the correct descriptive title for the current manuscript.
 	 */

	public function getDescriptiveTitle($doc) {
		$doc = str_replace(array('M', 's', '.', '(', 'O', 'I', 'M', ')'), '', $doc);
		$doc = ltrim($doc, '0');
		
		$xml = new DOMDocument();
		if (!$xml->load(realpath(dirname(__FILE__)) . '/../metadata/gms.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/ManuscriptList/msItem');
		
		foreach ($nl as $n) {
			$snl = $xp->query('manuscript', $n);
			$currentdoc = str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue);

			if ($currentdoc == $doc) {
				$title = '';
				$snl = $xp->query('catTitle', $n);
				foreach ($snl->item(0)->childNodes as $c) 
					$title .= $xml->saveXML($c);
				return $title;
			}
		}
		return sprintf("Ms. %s", ltrim($doc, '0'));
	}

	public function getPopularTitle($doc) {
		$doc = str_replace(array('M', 's', '.', '(', 'O', 'I', 'M', ')'), '', $doc);
		$doc = ltrim($doc, '0');
		
		$xml = new DOMDocument();
		if (!$xml->load(realpath(dirname(__FILE__)) . '/../metadata/gms.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/ManuscriptList/msItem');

		foreach ($nl as $n) {
			$snl = $xp->query('manuscript', $n);
			$currentdoc = str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue);

			if ($currentdoc == $doc) {
				$snl = $xp->query('other_name', $n);
				return $snl->item(0)->nodeValue;
			}
		}
		return '';
	}

	/*
 	 * input:
	 *   4 digit doc number
	 *   3 digit obj number
 	 * side effect:
	 *   prints caption for descriptive page.
 	 */

	function print_caption($doc, $obj = '001') {
		$xml = new DOMDocument();
		if (!$xml->load('../metadata/structural.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$q = sprintf('/dataroot/tblObjectStructuralRev[document="%s" and object="%s"]', $doc, $obj);
		$nl = $xp->query($q);

		$caption = sprintf("Ms. %s", ltrim($doc, '0'));
		$snl = $xp->query('folioPage1|folioPage2', $nl->item(0));
		if ($snl->length)
			$caption .= sprintf(", %s", $snl->item(0)->nodeValue);
		$snl = $xp->query('pagination1|pagination2', $nl->item(0));
		if ($snl->length)
			$caption .= sprintf(", %s", $snl->item(0)->nodeValue);
		$snl = $xp->query('physft', $nl->item(0));
		if ($snl->length)
			$caption .= sprintf(", %s", $snl->item(0)->nodeValue);
		print $caption;
	}

	/*
 	 * input:
	 *   4 digit doc number
	 * side effect:
 	 *   prints descriptive metadata for a doc.
 	 */

	function print_descriptive_content($doc) {
		$datafile = sprintf("../metadata/descriptive/%s.xml", $doc);
		if (file_exists($datafile)) {
			$xml = new DOMDocument();
			$xml->load($datafile);

			$xsl = new DOMDocument();
			$xsl->load('../xslt/descriptive.xsl');

			$xp = new XSLTProcessor();
			$xp->importStyleSheet($xsl);
			echo $xp->transformToXML($xml);
		} else {
			printf("<p style='margin-top: 4em;'>A full description for this manuscript is not yet available.</p>");
		}
	}

	function print_flipper($doc) {
		if ($this->contentExists($doc, 'description') && !strstr($_SERVER['REQUEST_URI'], 'descriptive.php'))
			$deschref = sprintf("<a href='../description/index.php?doc=%s'>Descriptive Metadata</a>", $doc);
		else
			$deschref = "Descriptive Metadata";
		if ($this->contentExists($doc, 'thumbs') && !strstr($_SERVER['REQUEST_URI'], 'thumbs.php'))
			$structhref = sprintf("<a href='../thumbs/index.php?doc=%s'>Thumbs</a>", $doc);
		else
			$structhref = "Thumbs";
		if ($this->contentExists($doc, 'zoomify')) 
			$viewhref = sprintf("<a href='../view/index.php?doc=%s&amp;obj=%s'>Zoomable Images</a>", $doc, '001');
		else
			$viewhref = "Zoomable Images";
		printf("<p>%s | %s | %s</p>", 
			$deschref, $structhref, $viewhref);
	}

	/*
 	 * check what web content is available for a given manuscript.
	 * input:	
	 *   doc: 4 digit doc number with leading zeros
	 *   content: key for content location array.
 	 */

	function contentExists($doc, $content) {
		switch ($content) {
			case 'description':
				return in_array($doc, $this->getDescriptiveDocList());
				break;
			case 'thumbs':
				return in_array($doc, $this->getThumbsDocList());
				break;
			case 'zoomify':
				return in_array($doc, $this->getScannedDocList());
				break;
		}
	}

	function search() {
		if ($this->clean['search'][0]) {
			/* build token / searchtype list for where query */
			$whereq = array();
			$s = 0;
			while ($s < count($this->clean['search'])) {
				if ($this->clean['search'][$s]) {
					if ($this->clean['searchfield'][$s] == 'searchall') 
						$whereq[] = sprintf("token='%s'",
							sqlite_escape_string($this->tokenize($this->clean['search'][$s])));
					else 
						$whereq[] = sprintf("(searchtype='%s' AND token='%s')",
							sqlite_escape_string($this->clean['searchfield'][$s]),
							sqlite_escape_string($this->tokenize($this->clean['search'][$s])));
				}
				$s++;
			}
			$whereq = implode(' OR ', $whereq);

			/* build doc list of intersection of all queries */
			$docq = array();
			$s = 0;
			while ($s < count($this->clean['search'])) {
				if ($this->clean['search'][$s]) {
					if ($this->clean['searchfield'][$s] == 'searchall')
						$docq[] = sprintf("SELECT DISTINCT doc FROM search WHERE token='%s'", 
							sqlite_escape_string($this->tokenize($this->clean['search'][$s])));
					else
						$docq[] = sprintf("SELECT DISTINCT doc FROM search WHERE (token='%s' and searchtype='%s')",
							sqlite_escape_string($this->tokenize($this->clean['search'][$s])),
							sqlite_escape_string($this->clean['searchfield'][$s]));
				}
				$s++;
			}
			$docq = implode(' INTERSECT ', $docq);

			$q = sprintf("SELECT searchtype, doc, min(relevance), snippet FROM search WHERE (%s) AND doc IN (%s) GROUP BY doc ORDER BY min(relevance), doc;\n",
				$whereq,
				$docq);

			$r = sqlite_query($this->db, $q);
			if (!$r)
				print 'trouble';
			return sqlite_fetch_all($r);
		} else {
			return array();
		}
	}

	function searchTitle() {
		if ($this->clean['search'][0]) {
			$searches = array(
			"searchall" => "the entire record", 
			"searchtitle" => "'Title or Type of Text'", 
			"searchcommonname" => "'Common Names of Manuscripts'", 
			"searchlocation" => "'Places of Origin or Association'", 
			"searchdate" => "'Dates of Origin'", 
			"searchlanguage" => "'Languages'", 
			"searchpersonalname" => "'Names of Individuals or Organizations'", 
			"searchmaterial" => "'Materials of Construction'", 
			"searchminiature" => "'Imagery'", 
			"searchminiaturekeyword" => "'Imagery Keyword'", 
			"searchbiblebks" => "'Books of the Bible'");

			/* build the title */
			$title = array();
			$s = 0;
			while ($s < count($this->clean['search'])) {
				if ($this->clean['search'][$s]) {
					if ($this->clean['searchfield'][$s] == 'searchall') 
						$title[] = sprintf(" '%s' in the entire record",
							htmlspecialchars($this->clean['search'][$s]));
					else
						$title[] = sprintf(" '%s' in %s",
							htmlspecialchars($this->clean['search'][$s]),
							$searches[$this->clean['searchfield'][$s]]);
				}
				$s++;
			}
			return implode(' and ', $title);
		} else {
			return '';
		}
	}

	public function tokenize($s) {
		$s = htmlentities($s, ENT_COMPAT, "UTF-8");
		$s = preg_replace ('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', $s);
		$s = html_entity_decode($s);

		$s = strtoupper($s);

		$s = str_replace(array("-", "_", "+", "'", "\"", "/", "[", "]", "(", ")", "!", "?", ",", ".", ":", ";"), "", $s);

		return $s;
	}

	/*
	 * input: 
	 *   utf-8 string.
	 * output: 
	 *   lowercase, special characters removed string. 
	 */

	public static function cleanString($s) {
	  $s = htmlentities($s, ENT_COMPAT, "UTF-8");
	  $s = preg_replace ('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', $s);
		// added 2009-10-20: one of the place browses uses this character.
		$s = str_replace('Ş', 'S', $s);
	  $s = html_entity_decode($s);
		$s = strtolower($s);
		return $s;
	}

	function listDocuments($list) {
		switch ($list) {
			case 'listcommonname':
				return getCommonNameDocuments();
				break;
			case 'listdescriptive':
				$docs = $this->getDescriptiveDocList();
				break;
			case 'listgregorynumber':
				return $this->listGregoryDocuments();
				break;
			case 'listscanned':
				$docs = $this->getScannedDocList();
				break;
		}
	
		$xml = new DOMDocument();
		if (!$xml->load(realpath(dirname(__FILE__)) . '/../metadata/gms.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/ManuscriptList/msItem');

		$results = array();
		foreach ($nl as $n) {
			$snl = $xp->query('manuscript', $n);
			$currentdoc = sprintf("%04d", trim(str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue)));
			if (in_array($currentdoc, $docs)) {
				$snl = $xp->query('other_name', $n);
				$title = sprintf("Ms. %s: %s", ltrim($currentdoc, '0'), $snl->item(0)->nodeValue);
				$results[] = array($currentdoc, $title);
			}
		}
		return $results;
	}

	/*
	 * alternate function for gregory number list. 
	 * it's very different from the other lists. */
	function listGregoryDocuments() {
		$xml = new DOMDocument();
		if (!$xml->load(realpath(dirname(__FILE__)) . '/../metadata/gms.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/ManuscriptList/msItem[gregory_number]');

		$results = array();
		foreach ($nl as $n) {
			$snl = $xp->query('manuscript', $n);
			$currentdoc = sprintf("%04d", trim(str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue)));

			$snl = $xp->query('gregory_number', $n);
			$gregorynumber = $snl->item(0)->nodeValue;
	
			$snl = $xp->query('other_name', $n);
			$commonname = $snl->item(0)->nodeValue;
	
			$title = sprintf("Gregory %s: %s", $gregorynumber, $commonname);

			$results[] = array($currentdoc, $title);
		}

		usort($results, 'cmpByGregoryNumbers');
		return $results;
	}

	function listCommonNameDocuments() {
		$xml = new DOMDocument();
		if (!$xml->load(realpath(dirname(__FILE__)) . '/../metadata/gms.xml'))
			die('problem with gms.xml');

		$xp = new DOMXPath($xml);
		$nl = $xp->query('/ManuscriptList/msItem[other_name]');

		$results = array();
		foreach ($nl as $n) {
			$snl = $xp->query('manuscript', $n);
			$currentdoc = sprintf("%04d", trim(str_replace(array('M', 's', '.', ' ', '(', 'O', 'I', 'M', ')'), '', $snl->item(0)->nodeValue)));
	
			$snl = $xp->query('other_name', $n);
			$commonname = $snl->item(0)->nodeValue;

			$results[] = array($currentdoc, $commonname);
		}

		usort($results, 'cmpByCommonName');
		return $results;
	}

	/*
	 * helper functions to sort lists by gregory number or alphabetically. 
	 */
	function cmpByGregoryNumbers($a, $b) {
	    if ($a[1] == $b[1]) {
	        return 0;
	    }
	    return ((int)$a[1] < (int)$b[1]) ? -1 : 1;
	}

	function cmpByCommonName($a, $b) {
	    if ($a[1] == $b[1]) {
	        return 0;
	    }
	    return ($a[1] < $b[1]) ? -1 : 1;
	}
}

?>
