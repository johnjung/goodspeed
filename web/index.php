<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/includes/$classname.php");
}

/*
 * ...Get a 'snippet' of a page to display as a teaser. 
 * The snippet is currently set to the first 30 words
 * of the first paragraph in the document. 
 */

function get_snippet($url) {
	$h = fopen($url, 'rb');
	$htmlstring = stream_get_contents($h);
	fclose($h);

	$dom = new DOMDocument();
	$dom->loadHTML($htmlstring);

	$xp = new DOMXPath($dom);
	// not id='breadcrumbs'
	$nl = $xp->query("//div[@id='maincolumn']//p");
	$snippetstring = $nl->item(0)->nodeValue;
	$snippetstring = implode(' ', array_slice(explode(' ', $snippetstring), 0, 30));
	return $snippetstring;
}

$navigation = new Navigation(new Cleaner());
$gms = new GMS(new Cleaner());
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta name="description" content="The Goodspeed Manuscript Collection
website comprises 68 early New Testament manuscripts. They are being
digitized in their entirety and presented as high-quality zoomable
images."/>
<title>The Goodspeed Manuscript Collection</title>
<meta property="fb:admins" content="492686530742610" />
<meta property="fb:app_id" content="493432004067588" />
<!-- HEAD -->
<?php include "templates/head.tmpl.php"; ?>
<link href="css/goodspeed-intropage.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<div id="header">

<div id="nametag">
<a href="http://www.lib.uchicago.edu/e/"><img
alt="The University of Chicago Library" src="http://goodspeed.lib.uchicago.edu/images/theuniversityofchicagolibrary.gif"/></a></div>

</div><!-- /HEADER -->

<!-- NAVIGATION -->
<div class="navigation" style="background: #800000; color: white;">
<p id='breadcrumbs'>Home</p>
</div>
	
<!-- SIDEBAR -->
<?php include "templates/sidebar.php"; ?>

<!-- MAIN COLUMN -->
<div id="maincolumn">
<img style="border: 1px solid #666; float: left; margin: 0 1.5em .75em 0; padding: 1px;" src="http://goodspeed.lib.uchicago.edu/images/gms-0931-061.jpg"/>
<p>The Edgar J. Goodspeed Manuscript Collection comprises 68 early Greek,
Syriac, Ethiopic, Armenian, Arabic, and Latin manuscripts ranging in date from
the 5th to the 19th centuries.  The acquisition of these hitherto unknown
manuscripts was spearheaded by Edgar J. Goodspeed in the first half of the
twentieth century in order to support new scholarship in the humanities.</p>

<p>With support from the <a href="http://www.imls.gov">Institute of Museum and
Library Services</a> National Leadership Grants for Libraries
- Building Digital Resources program, the University of Chicago Library is
  providing a unique digital resource based on this collection.  All 68 New
Testament manuscripts have been digitized
in their entirety and are presented with high-quality zoomable images through an
interface that supports browsing within individual manuscripts and across the
collection. The Goodspeed Manuscript Collection digitization continues the scholarly
tradition of the Goodspeed Collection itself and will support new types of
research and teaching made possible by digital technologies.</p>

<h2><a href="collection.php">About The Collection</a></h2>
<p><?php print get_snippet('collection.php'); ?>...
<a href="collection.php">(read more)</a></p>
<h2><a href="digitalproject.php">About The Digital Project</a></h2>
<p><?php print get_snippet('digitalproject.php'); ?>...
<a href="digitalproject.php">(read more)</a></p>
<h2><a href="reproductions.php">About Copyright and Reproductions</a></h2>
<p><?php print get_snippet('reproductions.php'); ?>...
<a href="reproductions.php">(read more)</a></p>
<h2><a href="reports.php">Progress and Assessment Reports</a></h2>
<p>View reports that describe the project&rsquo;s progress and its impact on
scholarly research.</p>

<h2><a href="http://www.lib.uchicago.edu/spcl">The University of Chicago
Library Special Collections Research Center</a></h2>
<p>Visit the home of the Goodspeed Manuscript Collection.</p>

<h2>Supported by:</h2>
<img alt="Institute of Museum and Library Services" src="http://goodspeed.lib.uchicago.edu/images/imlslogo.gif"/></p>

<p><i>Any views, findings, conclusions or recommendations expressed
in this website do not necessarily represent those of the Institute of
Museum and Library Services.</i></p>
</div><!--/MAIN COLUMN-->

<!-- FOOTER -->
<div id="footer">&nbsp;</div>

</div><!--/CONTENT-->
</body>
</html>
