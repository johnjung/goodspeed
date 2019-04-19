<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/includes/$classname.php");
}

$gms = new GMS(new Cleaner());
$navigation = new Navigation(new Cleaner());
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>The Goodspeed Manuscript Collection : Reproductions</title>
	<link href="css/goodspeed.css" type="text/css" rel="stylesheet"/>
	<link href="css/goodspeed-print.css" type="text/css" rel="stylesheet" media="print"/>
	<?php include "templates/head.tmpl.php"; ?>
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<?php include "templates/header.tmpl.php"; ?>

<!-- NAVIGATION -->
<div class="navigation">
<p id='breadcrumbs'><a href='/'>Home</a> &gt; Reproductions</p>
</div>

<!-- SIDEBAR -->
<?php include "templates/sidebar.php"; ?>

<!-- MAIN COLUMN -->
<div id="maincolumn">

<h1>Reproductions</h1>

<p>All materials in the Goodspeed Manuscript Collection may be used for educational and scholarly purposes, but any such use requires that a credit line be included with any material used.</p>

<p><strong>Credit Line:</strong><br/>
Goodspeed Manuscript Collection, [manuscript number-image number,
e.g., ms949-10], Special Collections Research Center, University of
Chicago Library.</p>

<p>Commercial publication projects require the permission of the University of Chicago Library and may be subject to a use fee. For permission to copy or use any part of the Goodspeed Manuscript Collection for any commercial purposes, please contact:</p>

<p>Special Collections Research Center<br/>
University of Chicago Library<br/>
1100 E. 57th Street<br/>
Chicago, Illinois 60637<br/>
Fax: (773) 702-3728</p>

<p><a href="http://www.lib.uchicago.edu/e/ask/SCRC.html">Contact the
Special Collections Research Center</a>.</p>

<p>Please direct questions or comments to <a href="mailto:goodspeed@lib.uchicago.edu">goodspeed@lib.uchicago.edu</a>.</p>

</div><!--/SECTION-->

<div id="footer"></div><!--/FOOTER-->

</div><!--/CONTENT-->

</body>
</html>

