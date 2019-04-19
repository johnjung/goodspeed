<?php

/*
 * GET URL PARAMS
 */

function cleaner() {
	$clean = array();

	$clean['doc'] = '';
	if (isset($_GET['doc'])) {
		if (ctype_digit($_GET['doc']) && strlen($_GET['doc']) <= 4)
			$clean['doc'] = sprintf("%04d", $_GET['doc']);
	}

	$clean['obj'] = '';
	if (isset($_GET['obj'])) {
		if (ctype_digit($_GET['obj']) && strlen($_GET['obj']) <= 3)
			$clean['obj'] = sprintf("%03d", $_GET['obj']);
	}

	$clean['nav'] = '';
	if (isset($_GET['nav'])) {
		if ($_GET['nav'] == 'on') 
			$clean['nav'] = 'on';
	}

	$clean['view'] = '';
	if (isset($_GET['view'])) {
		switch ($_GET['view']) {
			case 'thumbs':
			case 'metadata':
				$clean['view'] = $_GET['view'];
				break;
		}
	}
	return $clean;
}

?>
