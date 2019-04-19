$(document).ready(function() {
	/* add thumbnails */
	var doc = $(document).getUrlParam('doc');

	/* open appropriate json data file */
	$.getJSON("../metadata/json/structural/gms-" + doc + ".json", function(data) {
		var start = $(document).getUrlParam('start');
		if (!start)
			start = 0;
		var objectcount = data.length;

		/* create and append pager */
		var i = 0;
		var j = i + 100;
		var c = ' class="noborder"';
		var html = '';
		while (i < objectcount) {
			if (j >= objectcount)
				j = objectcount - 1;
			ipage = data[i]['caption'];
			jpage = data[j]['caption'];

			if (i == start) {
				html +=	'<span' + c + '>' + ipage + ' - ' + jpage + '</span>';
			} else {
				var url = 'index.php';
				url += '?start=' + i;
				url += '&doc=' + $(document).getUrlParam('doc');
				url += '&view=thumbs';
				if ($(document).getUrlParam('obj'))
					url += '&obj=' + $(document).getUrlParam('obj');
				else
					url += '&obj=001';
				html +=	'<span' + c + '><a href="' + url + '">' + ipage + ' - ' + jpage + '</a></span>';
			}
			c = '';
			i += 100;
			j += 100;
		}
		$('.pager').html('Viewing: ' + html);

		/* get first object */
		if (start)
			var i = parseInt(start);
		else		 
			var i = 0;

		/* get last object */
		var j = i + 100;
		if (j >= objectcount)
			j = objectcount - 1;

		/* append thumbs to document */
		var html = '';
		while (i <= j) {
			var href = '../view/index.php';
			href += '?doc=' + doc;
			href += '&obj=' + data[i]['object'];
			var src = 'http://goodspeed.lib.uchicago.edu/images/thumbs/gms-' + doc + '-' + data[i]['object'] + '.jpg';
			html += '<div class="thumbs"><a href="' + href + '"><img height="75" src="' + src + '"/><br/>' + data[i]['caption'] + '</a></div>';
			i++;
		}
		$('#thumbs').html(html);
	});	
});
