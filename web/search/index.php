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
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<?php include "../templates/header.tmpl.php"; ?>

<!-- NAVIGATION -->
<div class="navigation"><?php $navigation->printbreadcrumbs(); ?></div>

<!-- SIDEBAR -->
<?php include "../templates/sidebar.php"; ?>

<!-- MAINCOLUMN, INDIVIDUAL BROWSE -->
<div id="maincolumn">

<?php
if ($clean['search'][0]) {
	$results = $gms->search();
	printf("<h1>%d manuscripts contain search results for %s.</h1>", count($results), $gms->searchTitle());
	if (count($results)) {
		foreach ($results as $record) {
			printf("<h2 style='clear: left;'><a href='../ms/index.php?doc=%s'>%s</a></h2>", $record['doc'], $gms->getManuscriptTitle($record['doc']));
			printf("<p>%s (Relevance: %d%%)</p>", $record['snippet'], (1.0 - $record['min(relevance)']) * 100);
		}
	} else {
		printf("<p>There were no results for your search. Please try again, or browse the collection.</p>");
	}
} else {
?> 
<h1>Search</h1>
<p>Please enter a search term in the box at the right.</p>
<?php } ?>

</div>

<div id="footer"></div><!--/FOOTER-->
</div><!--/CONTENT-->

</body>
</html>

