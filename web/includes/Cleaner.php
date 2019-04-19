<?php

class Cleaner extends ArrayObject {
	function __construct() {
		// BROWSE
		if (isset($_GET['browse'])) 
			$this['browse'] = strip_tags($_GET['browse']);
	
		// BROWSETYPE
		if (isset($_GET['browsetype'])) {
			$this['browsetype'] = '';
			switch ($_GET['browsetype']) {
				case 'browsebiblebks':
				case 'browsecommonname':
				case 'browsedate':
				case 'browselanguage':
				case 'browselocation':
				case 'browsematerial':
				case 'browseminiature':
				case 'browsepersonalname':
				case 'browsetitle':
					$this['browsetype'] = $_GET['browsetype'];
					break;
			}
		}

		// DOC
		if (isset($_GET['doc'])) {
			$this['doc'] = '';
			if (ctype_digit($_GET['doc']) && strlen($_GET['doc']) <= 4)
				$this['doc'] = sprintf("%04d", $_GET['doc']);
		}

		// OBJ
		$this['obj'] = '001';
		if (isset($_GET['obj'])) {
			$this['obj'] = '';
			if (ctype_digit($_GET['obj']) && strlen($_GET['obj']) <= 3)
				$this['obj'] = sprintf("%03d", $_GET['obj']);
		}
	
		// LIST
		if (isset($_GET['list'])) {
			$this['list'] = '';
			switch ($_GET['list']) {
				case 'listdescriptive':
				case 'listgregorynumber':
				case 'listscanned':
					$this['list'] = $_GET['list'];
					break;
			}
		}

		// SEARCH
		$this['search'] = array_fill(0, 3, '');
		$s = 0;
		if (isset($_GET['search'][0])) {
			while ($s < count($_GET['search'])) {
				if (!($_GET['search'][$s]))
					break;
				// hack to get archaic mark to search correctly.
				$words = explode(' ', $_GET['search'][$s]);
				$this['search'][$s] = $words[0];
				//$this['search'][$s] = $_GET['search'][$s];
				$s++;
			}
		}

		// SEARCHFIELD
		$this['searchfield'] = array_fill(0, 3, 'searchall');
		if (isset($_GET['searchfield'])) {
			$s = 0;
			while ($s < count($_GET['searchfield'])) {
				switch ($_GET['searchfield'][$s]) {
					case "searchall":
					case "searchtitle":
					case "searchcommonname":
					case "searchlocation":
					case "searchdate":
					case "searchlanguage":
					case "searchpersonalname":
					case "searchmaterial":
					case "searchminiature":
					case "searchminiaturekeyword":
					case "searchbiblebks":
						$this['searchfield'][$s] = $_GET['searchfield'][$s];
						break;
				}
				$s++;
			}
		}

		// SHOW
		$this['show'] = 'all';
		if (isset($_GET['show'])) {
			switch($_GET['show']) {
				case 'all':
				case 'scanned':
					$this['show'] = $_GET['show'];
					break;
			}
			
		}

		// SORT
		if (isset($_GET['sort'])) {
			$this['sort'] = 'asc';
			switch ($_GET['sort']) {
				case 'asc':
				case 'dsc':
				case 'countasc':
				case 'countdsc':
					$this['sort'] = $_GET['sort'];
					break;
			}
		}

		// START
		$this['start'] = '001';
		if (isset($_GET['start'])) {
			$this['start'] = '';
			if (ctype_digit($_GET['start']) && strlen($_GET['start']) <= 3)
				$this['start'] = sprintf("%03d", $_GET['start']);
		}

		// VIEW
		if (isset($_GET['view'])) {
			$this['view'] = 'description';
			switch($_GET['view']) {
				case 'description':
				case 'thumbs':
					$this['view'] = $_GET['view'];
					break;
			}
		}
	}
}
?>
