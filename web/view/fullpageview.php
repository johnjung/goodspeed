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
<title>Goodspeed Manuscript Collection</title>
<link href="/css/view.css" rel="stylesheet" type="text/css" />
</head>
<body onload="window.focus();">
<!-- ZOOMIFY VIEWER -->
<object
 classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
 codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
 width="100%"
 height="100%"
 id="zoomifyviewer"
 align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="movie" value="zoomifyviewer.swf" />
	<param name="flashVars" value="<?php echo get_flashvars(); ?>" />
	<param name="quality" value="high" />
	<param name="scale" value="noscale" />
	<param name="bgcolor" value="#ffffff" />
	<embed
	 src="zoomifyviewer.swf"
	 flashvars="<?php echo get_flashvars(); ?>"
	 quality="high"
	 scale="noscale"
	 bgcolor="#ffffff"
	 width="100%"
	 height="100%"
	 name="zoomifyviewer"
	 align="middle"
	 allowScriptAccess="sameDomain"
	 type="application/x-shockwave-flash"
	 pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
</body>
</html>

