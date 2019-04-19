<?php

class Navigation {

	/* DICTIONARY OF PAGE TITLES
	 * Each key is a title, and the value is a regular expression
 	 * to match urls. 
 	 */
	private $titles = array(
		array(
			'text' => 'Home',
			'href' => '/'),
		array(
			'text' => 'All Digitized Manuscripts',
			'href' => '/list/index\.php'),
		array(
			'text' => 'Browse the Collection',
			'href' => '/browse/index\.php'),
		array(
			'text' => 'Browse Books of the Bible',
			'href' => '/browse/index\.php\?.*browsetype=browsebiblebks'),
		array(
			'text' => 'Browse Common Names of Manuscripts',
			'href' => '/browse/index\.php\?.*browsetype=browsecommonname'),
		array(
			'text' => 'Browse Dates of Origin', 
			'href' => '/browse/index\.php\?.*browsetype=browsedate'),
		array(
			'text' => 'Browse Imagery', 
			'href' =>'/browse/index\.php\?.*browsetype=browseminiature'),
		array(
			'text' => 'Browse Imagery Keyword', 
			'href' =>'/browse/index\.php\?.*browsetype=browseminiaturekeyword'),
		array(
			'text' => 'Browse Languages', 
			'href' => '/browse/index\.php\?.*browsetype=browselanguage'),
		array(
			'text' => 'Browse Materials of Construction', 
			'href' => '/browse/index\.php\?.*browsetype=browsematerial'),
		array(
			'text' => 'Browse Names of Individuals or Organizations', 
			'href' => '/browse/index\.php\?.*browsetype=browsepersonalname'),
		array(
			'text' => 'Browse Places of Origin or Association', 
			'href' => '/browse/index\.php\?.*browsetype=browselocation'),
		array(
			'text' => 'Browse Title or Type of Text', 
			'href' => '/browse/index\.php\?.*browsetype=browsetitle'),
		array(
			'text' => 'All Documents With Thumbnails', 
			'href' => '/list/index\.php\?.*list=listthumbs'),
		array(
			'text' => 'Search',
			'href' => '/search/index\.php'));

	/* DICTIONARY OF FILE LOCATIONS.
	 * Each of the values in the array below are formatted strings. 
	 * replacing the %s with a 4 digit document number gives a path-
	 * if that path exists, then we know that kind of content exists.
 	 */
	private $contentlocation = array(
		'description' 
		=> '../metadata/descriptive/%s.xml',
		'thumbs' 
		=> '/projects/goodspeed/web/images/thumbs/gms-%s-001.jpg',
		'zoomify' 
		=> '/projects/goodspeed/web/images/%s');

	// USER SUPPLIED INPUT (CLEANED)
	private $clean;

	/* SET UP BREADCRUMB TRAILS.
	 * If the user is on the homepage, browse home page, a browse page,
 	 * or a list page, we know the breadcrumb trail. 
	 * If the user is on an ms page, check the history to see if there
 	 * is a browse in there. If so, put it in the new history. If not,
	 * make the list of all scanned manuscripts the default breadcrumb. 
	 * 
	 * Testing:
	 * disabled cookies. 
	 * bookmarked manuscript page directly.
	 *
	 * Notes: 
	 * when you get a page with curl, you still get the SERVER superglobal. 
	 * you can use sessions. However, you have to do some extra work to 
	 * provide the session id with curl. See the --cookie & --cookie-jar options.
	 */

	function __construct($clean) {
		$this->clean = $clean;

		session_start();
    
		$url = $_SERVER["REQUEST_URI"];

		if ($url == '/' || $url == '/index.php') {
			$this->history = array($url);
			$_SESSION['history'] = serialize($this->history);
		/* if we're on the browse index page, 
		 * rebuild the breadcrumb trail from this page. */
		} else if ($url == '/browse/index.php') {
			$this->history = array(
				parse_url($url, PHP_URL_HOST) . '/',
				parse_url($url, PHP_URL_HOST) . '/browse/index.php');
		/* if we're on a browse result page,
		 * rebuild the breadcrumb trail. Insert the home page, and
		 * stick the list of all browses into the middle of the trail. */
		} else if (array_key_exists('browsetype', $clean)) {
			$this->history = array(
				parse_url($url, PHP_URL_HOST) . '/',
				parse_url($url, PHP_URL_HOST) . '/browse/index.php',
				$url);
			$_SESSION['history'] = serialize($this->history);
		/* if we're on a list page,
		 * rebuild the breadcrumb trail. Insert the home page and the list page. */
		} else if (array_key_exists('list', $clean)) {
			$this->history = array(
				parse_url($url, PHP_URL_HOST) . '/',
				$url);
			$_SESSION['history'] = serialize($this->history);
		/* if we're on a serch result page,
 		 * rebuild the breadcrumb trail. Insert the home page and the url for this search. */
		} else if (array_key_exists('search', $clean)) {
			$this->history = array(
				parse_url($url, PHP_URL_HOST) . '/',
				$url);
			$_SESSION['history'] = serialize($this->history);
		/* otherwise we must be on a manuscript page. Remember however we got here,
		 * and add the current page to the breadcrumb trail. */
		} else {
			if (array_key_exists('history', $_SESSION)) {
				$this->history = unserialize($_SESSION['history']);
				if (count($this->history) > 0) { 
					if (strstr($this->history[count($this->history) - 1], '/ms/') !== false)
						array_pop($this->history);
				}
				$this->history[] = $url;
			} else {
				$this->history = array(
					parse_url($url, PHP_URL_HOST) . '/',
					$url
				);
			}
			$_SESSION['history'] = serialize($this->history);
			/* if the final piece of the breadcrumb trail is a manuscript,
			 * replace it with whatever manuscript we're looking at right now. */
		}
	}

	/*
 	 * helper function.
	 * This digs through a set of urls and checks to see if a parameter exists. 
	 * if it does, return the entire url. 
 	 */
	function checkparams($urls, $p) {
		foreach ($urls as $url) {
			$c = $this->checkparam($url, $p);
			if ($c)
				return $c;
		}
		return false;
	}
	
	function checkparam($url, $p) {
		$params = array();
		parse_str(parse_url($url, PHP_URL_QUERY), $params);
		if (array_key_exists($p, $params))
			return $url;
		else 
			return false;
	}

	/*
	 * Either select or create the appropriate title for the current page.
	 * input:
	 *   none. We figure out the title based on the url.
	 * output:
	 *   returns a title string.
	 * notes:
	 *   First, we check to see if the current url is for a description
	 *   or thumbs page. If so, we need to build the title
	 *   programmatically. If not, we look for the longest match in the
	 *   titles variable. 
   	 */

	function gettitle($href) {
		$len = 0;
		$txt = '';
		if (eregi('/ms/', $href)) {
			$txt = sprintf("Ms. %s", ltrim($this->getparam($href, 'doc'), '0'));
		} else {
			foreach ($this->titles as $t) {
				if (eregi($t['href'], $href) && strlen($t['href']) > $len) {
					$txt = $t['text'];
					$len = strlen($t['href']);
				}
			}	
		}
		return $txt;
	}

	function getparam($href, $param) {
		// GET THE PARAMS INTO A STRING.
		$t = parse_url($href);
		$strparams = $t['query'];

		$rawparams = explode('&', $strparams);
		$params = array();
		foreach ($rawparams as $r) {
			$t = explode('=', $r);
			$params[$t[0]] = $t[1];
		}
		return $params[$param];
	}

	/*
 	 * step through crumbs one at a time, and print them.
	 * this method will print parameters if it has to.
	 */ 

	function printbreadcrumbs() {
		$crumbs = array();
		foreach ($this->history as $h) {
			$crumbs[] = array('href' => $h, 'text' => $this->gettitle($h));
		}
		$strcrumbs = array();	
		$c = 0;
		foreach ($crumbs as $crumb) {
			if ($c < count($crumbs) - 1) 
				$strcrumbs[] = sprintf("<a href='%s'>%s</a>", $crumb['href'], $crumb['text']);
			else 
				$strcrumbs[] = sprintf("%s", $crumb['text']);
			$c++;
		}
		printf("<p id='breadcrumbs'>%s</p>", implode(" &gt; ", $strcrumbs));
	}

	/*
 	 * this will have to do a database lookup for manuscript pages, to get judith's 
 	 * proper title.
	 */
	function printtitle() {
		printf("<h1>%s</h1>", $this->gettitle($_SERVER['REQUEST_URI']));
	}

	/*
 	 * Print browse sorts 
	 */

	function printbrowsesorts($sorttext) {
		printf("<p id='flipper'>Sort: %s | %s</p>",
			$this->printlink('asc', $sorttext),
			$this->printlink('countdsc', 'By number of results'));
	}

	function printlink($sort, $sorttext) {
		if ($this->clean['sort'] == $sort)
			return $sorttext;
		else
			return sprintf("<a href='index.php?browsetype=%s&amp;sort=%s'>%s</a>", $this->clean['browsetype'], $sort, $sorttext);
	}

	/*
 	 * Print browse 'show' (scanned or not)
	 */

	function printbrowsescanned() {
		printf("<p id='showscanned'>Show: ");
		if ($this->clean['show'] == 'all') {
			printf("All manuscripts | <a href='index.php?browsetype=%s&amp;sort=%s&amp;show=scanned'>Digitized manuscripts only</a></p>",
				$this->clean['browsetype'], $this->clean['sort']);
		} else {
			printf("<a href='index.php?browsetype=%s&amp;sort=%s;&amp;show=all'>All manuscripts</a> | Digitized manuscripts only</p>",
				$this->clean['browsetype'], $this->clean['sort']);
		}
	}

	function contentExists($content, $doc = false) {
		if (!$doc)
			$doc = $this->clean['doc'];
		return file_exists(sprintf($this->contentlocation[$content], $doc));
	}
}

?>
