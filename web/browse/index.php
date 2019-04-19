<?php 
function __autoload($classname) {
  require_once(realpath(dirname(__FILE__)) . "/../includes/$classname.php");
}

$clean = new Cleaner();
$gms = new GMS($clean);
$navigation = new Navigation($clean);
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>The Goodspeed Manuscript Collection</title>
<!-- HEAD -->
<?php include "../templates/head.tmpl.php"; ?>
<script src="../scripts/jquery.goodspeedbrowse.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	var browsetype = $(document).getUrlParam('browsetype');
	if (browsetype) {
		$('#maincolumn').goodspeedbrowse({
			'browsetype': browsetype
		});
	}
});
</script>
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<?php include "../templates/header.tmpl.php"; ?>

<!-- NAVIGATION -->
<div class="navigation">
<?php 
if (array_key_exists('browsetype', $clean)) { 
$navigation->printbreadcrumbs(); 
} else { ?>
<p id='breadcrumbs'><a href='/'>Home</a> &gt; Browse the Collection</p>
<?php } ?>
</div>

<!-- SIDEBAR -->
<?php include "../templates/sidebar.php"; ?>

<!-- MAINCOLUMN, INDIVIDUAL BROWSE -->
<div id="maincolumn">

<h1>Browse the Collection</h1>

<dl>
<dt><a href="/list/index.php?list=listscanned">All Digitized Manuscripts</a></dt>
<dd/>
<dt><a href="/browse/index.php?browsetype=browsetitle&amp;sort=asc">Title or Type of Text</a></dt>
<dd/>
<dt><a href="/browse/index.php?browsetype=browsecommonname&amp;sort=asc">Common Names of Manuscripts</a></dt>
<dd>Titles in common use derived from the names of scribes, former owners,
and donors associated with the manuscript; or related to specific
content or places of origin.</dd>
<dt><a href="/browse/index.php?browsetype=browselocation&amp;sort=asc">Places of Origin or Association</a></dt>
<dd>Geographic sites where the manuscripts were created, discovered, or
purchased.</dd>
<dt><a href="/browse/index.php?browsetype=browsedate&amp;sort=asc">Dates of Origin</a></dt>
<dd/>
<dt><a href="/browse/index.php?browsetype=browselanguage&amp;sort=asc">Languages</a></dt>
<dd/>
<dt><a
href="/browse/index.php?browsetype=browsepersonalname&amp;sort=asc">Names
of Individuals or Organizations</a></dt>
<dd>Names of individuals or organizations associated with the manuscripts,
such as scribes, artists, patrons, donors, owners, and booksellers.</dd>
<dt><a href="/browse/index.php?browsetype=browsematerial&amp;sort=asc">Materials of Construction</a></dt>
<dd/>
<dt><a href="/browse/index.php?browsetype=browseminiature&amp;sort=asc">Imagery</a></dt>
<dd/>
<dt><a
href="/browse/index.php?browsetype=browseminiaturekeyword&amp;sort=asc">Imagery Keyword</a></dt>
<dd/>
<dt><a href="/browse/index.php?browsetype=browsebiblebks&amp;sort=asc">Books of the Bible</a></dt>
<dd/>
</dl>

</div>

<div id="footer"></div><!--/FOOTER-->
</div><!--/CONTENT-->

</body>
</html>
