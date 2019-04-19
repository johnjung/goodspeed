<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/../includes/$classname.php");
}

$clean = new Cleaner();
$navigation = new Navigation($clean);
$gms = new GMS($clean);
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>The Goodspeed Manuscript Collection</title>
	<!-- HEAD -->
	<?php include "../templates/head.tmpl.php"; ?>
	<script src="../scripts/tools.tabs-1.0.4.js" type="text/javascript"></script>
	<script src="../scripts/goodspeedms.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		var initialIndex = 0;
		if ($(document).getUrlParam('view') == 'thumbs') {
			initialIndex = $('.tablink').index($('#thumbnaillink'));
		} else if ($(document).getUrlParam('view') == 'description') {
			initialIndex = $('.tablink').index($('#descriptionlink'));
		}
		if (initialIndex < 0)
			initialIndex = 0;
		var t = $('ul#tabs').tabs('div.tab', {
			initialIndex: initialIndex,
			tabs: 'a.tablink'
		});
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
<?php $navigation->printbreadcrumbs(); ?>
</div>

<!-- SIDEBAR -->
<?php include "../templates/sidebar.php"; ?>

<!-- MAINCOLUMN -->
<div id="maincolumn">

<h1 id='cattitle'><?php echo $gms->getDescriptiveTitle($clean['doc']); ?></h1>

<?php if ($gms->contentExists($clean['doc'], 'thumbs') ||
          $gms->contentExists($clean['doc'], 'description') ||
          $gms->contentExists($clean['doc'], 'zoomify')) { ?>

<ul id="tabs">
<?php if ($gms->contentExists($clean['doc'], 'thumbs')) { ?>
<li><a class="tablink" id="thumbnaillink" href="#thumbnailtab">Thumbnails</a></li>
<?php } ?>

<?php if ($gms->contentExists($clean['doc'], 'description')) { ?>
<li><a class="tablink" id="descriptionlink" href="#descriptiontab">Description</a></li>
<?php } ?>

<?php if ($gms->contentExists($clean['doc'], 'zoomify')) { ?>
<li><a href="../view/index.php?doc=<?php echo $clean['doc']; ?>&amp;obj=<?php echo $clean['obj']; ?>">Zoomable Images</a></li>
<?php } ?>
</ul>

<?php if ($gms->contentExists($clean['doc'], 'thumbs')) { ?>
<div class="tab" id="thumbnailtab"><p class="pager"></p><div id="thumbs"></div><p class="pager" style="clear: left;"></p></div>
<?php } ?>

<?php if ($gms->contentExists($clean['doc'], 'description')) { ?>
<div class="tab" id="descriptiontab">
<?php if (!$gms->contentExists($clean['doc'], 'zoomify')) { ?><p style="padding-top: 2em;">Note: <em>Digital images for this manuscript are not yet available.</em></p><?php } ?>
<?php $gms->print_descriptive_content($clean['doc']); ?>
</div>
<?php } ?>

<?php } else { ?>
<p>Digital images or a full description of this manuscript are not yet available.</p>
<?php } ?>

</div><!--/MAINCOLUMN-->
<div id="footer"></div><!--/FOOTER-->
</div><!--/CONTENT-->

</body>
</html>
