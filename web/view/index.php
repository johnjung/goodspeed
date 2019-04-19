<?php

require_once('cleaner.php');
$clean = cleaner();

require_once('printer.php');

?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<title>The Goodspeed Manuscript Collection</title>
<link href="../css/view.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/view.js"></script>
<script type="text/javascript">
// ASYNCHRONOUS GOOGLE ANALYTICS CODE.
// http://code.google.com/apis/analytics/docs/tracking/asyncTracking.html
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-12414285-1']);
_gaq.push(['_trackPageview']);

(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
})();
</script>
</head>
<body>

<!-- NAMETAG -->
<div id="nametag"> 
<a href="http://www.lib.uchicago.edu/e/">
<img src="/images/theuniversityofchicagolibrary.gif" alt="The University of Chicago Library"/>
</a>
</div>

<!-- HEADER (TOP HALF: BREADCRUMBS, RED STRIPE) -->
<div id="header">
<div style="position: absolute; bottom: 0; border-bottom: 1px solid #ddd; width: 100%; padding-bottom: 4px;">
<p>
<a href="/"><strong>The Goodspeed
Manuscript Collection</strong></a> |
<a href="../ms/index.php?doc=<?php echo $clean['doc']; ?>">Ms. <?php print_ms_number(); ?></a> | 
<a href="../ms/index.php?doc=<?php echo $clean['doc'];
?>&view=description">description</a> | 
image <?php print_image_number(); ?> of 
<?php print_image_count(); ?></p>
</div>
</div><!-- / HEADER -->

<!-- CONTROLLER (BOTTOM HALF: LINKS TO CONTROL FLASH PLAYER) -->
<div id="controller">
<form id="zoomifycontroller" action="index.php">
<input type="hidden" name="doc" id="doc" value="<?php echo $clean['doc']; ?>"/>
<p>
<a href="<?php print_reset_link(); ?>" id="resetview">Reset View</a> |
<a href="<?php print_toggle_link(); ?>" id="togglenavwindow">Toggle Navigation Window</a> | 
Jump to image
<input type="text" name="obj" id="obj"/>
of 
<?php print_image_count(); ?>
</p>
</form>
</div>

<!-- IFRAME FOR ZOOMIFY VIEWER -->
<iframe frameborder="0" id="zoomifyframe" src="<?php print_zoomify_html(); ?>" scrolling="no"></iframe>

<!-- FOOTER: METADATA VIEW -->
<div id="footer">
<p><?php print_metadata_line(); ?></p>
</div>

</body>
</html>
