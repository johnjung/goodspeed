<?php

function print_ms_number() {
	global $clean;
	print ltrim($clean['doc'], '0');
}

function print_image_number() {
	global $clean;
	print ltrim($clean['obj'], '0');
}

function print_image_count() {
	global $clean;
	$objects = json_decode(file_get_contents(sprintf('../metadata/json/structural/gms-%s.json', $clean['doc'])), true);
	print count($objects);
}

function print_reset_link() {
	print($_SERVER['REQUEST_URI']);
}

function print_toggle_link() {
	global $clean;
	if ($clean['nav'])
		printf("index.php?doc=%s&amp;obj=%s", $clean['doc'], $clean[ 'obj']);
	else
		printf("index.php?doc=%s&amp;obj=%s&amp;nav=on", $clean['doc'], $clean['obj']);
}

function print_thumbnail_link() {
	global $clean;
	if ($clean['nav']) {
		printf("index.php?doc=%s&amp;obj=%s&amp;view=thumbs&amp;nav=on", $clean['doc'], $clean['obj']);
	} else {
		printf("index.php?doc=%s&amp;obj=%s&amp;view=thumbs", $clean['doc'], $clean['obj']);
	}
}

function print_zoomify_html() {
	global $clean;
	if ($clean['view'] == '') {
		if ($clean['nav'])
			printf("fullpageview.php?doc=%s&amp;obj=%s&amp;nav=on", $clean['doc'], $clean['obj']);
		else
			printf("fullpageview.php?doc=%s&amp;obj=%s", $clean['doc'], $clean['obj']);
	} 
	if ($clean['view'] == 'thumbs') {
		if ($clean['nav'])
			printf("thumbnailview.php?doc=%s&amp;obj=%s&amp;nav=on", $clean['doc'], $clean['obj']);
		else
			printf("thumbnailview.php?doc=%s&amp;obj=%s", $clean['doc'], $clean['obj']);
	} 
}

function print_metadata_line() {
	global $clean;
	$objects = json_decode(file_get_contents(sprintf('../metadata/json/structural/gms-%s.json', $clean['doc'])), true);
	foreach ($objects as $object) {
		if ($object['object'] == $clean['obj']) {
			print $object['caption'];
			return;
		}
	}
}

/*
 * get link for page turning.
 * input: integer object offset (e.g. -1 = previous page, 1 = next page)
 * also uses global METADATA and clean.
 * output: either a blank string or a link to another page. 
 */

function get_pageturning_link($offset) {
	global $clean;

	$i = (int)$clean['obj'] + $offset;
	$checkdir = sprintf('/projects/goodspeed/web/images/%s/gms-%s-%03s', $clean['doc'], $clean['doc'], $i);
	if (!file_exists($checkdir))
		return '';
	else
		return sprintf("index.php?doc=%s&obj=%03s",$clean['doc'],$i);
}

function get_image_count() {
	return '';
}

/*
 * get flashvars.
 * input: none.
 * uses global METADATA and clean.
 * output: flashvars string.
 */

function get_flashvars() {
	global $clean;
	
	$f = array();
	/*
 	 * Ms. 995 and 125 are scrolls. Start them off more 'zoomed-in' than
 	 * the other manuscripts.
	 */
	if ($clean['doc'] == '0995' || $clean['doc'] == '0125') {
		$f[] = 'zoomifyZoom=25';
		$f[] = 'zoomifyY=0.45';
	}
	$f[] = 'imagepath=' . urlencode(sprintf("/images/%s/gms-%s-%s", $clean['doc'], $clean['doc'], $clean['obj']));
	$f[] = 'prevlink=' . urlencode(get_pageturning_link(-1));
	$f[] = 'nextlink=' . urlencode(get_pageturning_link(1));
	$f[] = 'imagecount=' . urlencode(get_image_count());
	if (isset($METADATA)) 
		$f[] = 'metadata=' . urlencode($METADATA[$clean['obj']][1]);
	if ($clean['nav'])
		$f[] = 'nav=on';
	else if ($clean['doc'] == '0995' || $clean['doc'] == '0125')
		$f[] = 'nav=on';
	else
		$f[] = 'nav=off';
	return implode("&amp;", $f);
}

?>
