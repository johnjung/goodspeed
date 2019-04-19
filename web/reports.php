<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/includes/$classname.php");
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
<title>The Goodspeed Manuscript Collection</title>
<link href="css/goodspeed.css" rel="stylesheet" type="text/css"/>
<link href="css/goodspeed-print.css" rel="stylesheet" type="text/css" media="print"/>
<?php include "templates/head.tmpl.php"; ?>
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<?php include "templates/header.tmpl.php"; ?>

<!-- NAVIGATION -->
<div class="navigation">
<p id='breadcrumbs'><a href='/'>Home</a> &gt; Progress and Assessment Reports</p>
</div>
	
<!-- SIDEBAR -->
<?php include "templates/sidebar.php"; ?>

<!-- MAIN COLUMN -->
<div id="maincolumn">
<h2>Progress and Assessment Reports</h2>
<ul>
	<li><a href="reports/IMLSinterimrptmay06.doc">
	IMLS Interim Report covering October 31, 2005 - April 30, 2006</a></li>
	<li><a href="reports/IMLSinterimrptoct06.doc">
	IMLS Interim Report covering May 1 - October 31, 2006</a></li>
	<li><a href="reports/IMLSinterimrptapr07.doc">
	IMLS Interim Report covering October 1 - March 31, 2007</a></li>
	<li><a href="reports/IMLSinterimrptoct07.doc">
	IMLS Interim Report covering April 1 - September 30, 2007</a></li>
	<li><a href="reports/IMLSinterimrptmay08.doc">
  IMLS Interim report covering October 1, 2007 - March 31, 2008</a></li>
	<li><a href="reports/IMLSinterimrptoct08.doc">
  IMLS Interim report covering April 1, 2008 - September 30, 2008</a></li>
	<li><a href="reports/IMLSinterimrptmay09.doc">
  IMLS Interim report covering October 1, 2008 - March 31, 2009</a></li>
	<li><a href="reports/GATscholarlyresearchimpact.docx">
	Outcomes Assessment Measure: Impact on Larger Library and Scholarly Community</a></li>
	<li><a href="reports/GATklauckclass.doc">Outcomes Assessment Measure: Use of Digitized Manuscript Materials in Teaching and Research</a></li>
	<li><a href="reports/GATusability.doc">Usability Test Results</a></li>
</ul>
</div><!--/MAIN COLUMN-->

<!-- FOOTER -->
<div id="footer">&nbsp;</div>

</div><!--/CONTENT-->
</body>
</html>
