<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/../includes/$classname.php");
}

$clean = new Cleaner();
$gms = new GMS($clean);
$navigation = new Navigation($clean, true);
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
<div class="navigation">
<p id='breadcrumbs'><a href='/'>Home</a> &gt; All Digitized Manuscripts</p>
</div>

<!-- SIDEBAR -->
<?php include "../templates/sidebar.php"; ?>

<!-- MAINCOLUMN -->
<div id="maincolumn">

<?php switch ($clean['list']) {
	case 'listscanned':
?><h1>All Digitized Manuscripts</h1><?php
		break;
	case 'listdescriptive':
?><h1>Manuscripts with Descriptive Metadata</h1><?php
		break;
	case 'listgregorynumbers':
?><h1>Manuscripts by Gregory Number</h1><?php
		break;
} ?>

<ul>
<?php foreach($gms->listDocuments($clean['list']) as $doc) { ?>
<li><a href="../ms/index.php?doc=<?php print $doc[0]; ?>"><?php print $doc[1]; ?></a></li>
<?php } ?>
</ul>

</div><!--/MAINCOLUMN-->
<div id="footer"></div><!--/FOOTER-->
</div><!--/CONTENT-->

</body>
</html>
