<link href="/css/goodspeed.css" type="text/css" rel="stylesheet"/>
<link href="/css/goodspeed-print.css" type="text/css" rel="stylesheet" media="print"/>
<link href="/css/jquery.modal.css" type="text/css" rel="stylesheet"/>
<script src="/scripts/jquery-1.3.2.js" type="text/javascript"></script>
<script src="/scripts/jquery.getUrlParam.js" type="text/javascript"></script>
<script src="/scripts/jquery.corner.js" type="text/javascript"></script>
<script src="/scripts/jquery.modal.js" type="text/javascript"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-12414285-1', 'auto');
  /* Enhanced link attribution */
  ga('require', 'linkid');
  /* Anonymize IP */
  ga('set', 'anonymizeIp', true);

  $(document).ready(function() {
    var q = 'https://www.lib.uchicago.edu/cgi-bin/subnetclass?jsoncallback=?';
    $.getJSON(q, function(data) {
      // Store subnetclass. Must send the pageview event after this. 
      ga('set', 'dimension1', data);
      ga('send', 'pageview');
    }); 
  });
</script>
<script type="text/javascript">
$(document).ready(function() {

	// ROUND CORNERS ON INTERFACE ELEMENTS.
	$('#advancedsearchoverlay').corner();

	// MODAL WINDOW FOR ADVANCED SEARCH.
	$('#advancedsearchoverlay').jqm({
		trigger: 'a#advancedsearchlink'
	});
	
	// CLOSE BUTTON FOR MODAL WINDOW.
	$('#advancedsearchclose').click(function() {
		$('#advancedsearchoverlay').jqmHide();
			return false;
		});
	});
</script>
