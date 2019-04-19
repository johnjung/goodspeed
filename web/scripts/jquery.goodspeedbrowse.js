(function($) {
	$.fn.extend({
		goodspeedbrowse: function(options) {
			var options = $.extend({
				browsetype: ''
			}, options);
		
			if (!options.browsetype)
				return;

			var container = this;

			// clear container
			$(container).html('');
	
			switch (options.browsetype) {
				case 'browsetitle':
					$(container).append('<h1>Title or Type of Text</h1>');
					break;
				case 'browsecommonname':
					$(container).append('<h1>Common Names of Manuscripts</h1><p>Titles in common use derived from the names of scribes, former owners, and donors associated with the manuscript; or related to specific content or places of origin.</p>');
					break;
				case 'browselocation':
					$(container).append('<h1>Places of Origin or Association</h1><p>Geographic sites where the manuscripts were created, discovered, or purchased.</p>');
					break;
				case 'browsedate':
					$(container).append('<h1>Dates of Origin</h1>');
					break;
				case 'browselanguage':
					$(container).append('<h1>Languages</h1>');
					break;
				case 'browsepersonalname':
					$(container).append('<h1>Names of Individuals or Organizations</h1><p>Names of individuals or organizations associated with the manuscripts, such as scribes, artists, patrons, donors, owners, and booksellers.</p>');
					break;
				case 'browsematerial':
					$(container).append('<h1>Materials of Construction</h1>');
					break;
				case 'browseminiature':
					$(container).append('<h1>Imagery</h1>');
					break;
				case 'browseminiaturekeyword':
					$(container).append('<h1>Imagery Keyword</h1>');
					break;
				case 'browsebiblebks':
					$(container).append('<h1>Books of the Bible</h1>');
					break;
			}

			// get browse data
			$.getJSON("../metadata/json/" + options.browsetype + ".json", function(data) {
				var html = '<ul>';
 				$.each(data, function(i, browse) {  
					$.each(browse, function(title, records) {
						html += '<li class="collapsed"><a href="#">' + title + ' (' + records.length + ')</a><ul class="expandable">';
						$.each(records, function(i, record) {
							var obj = '001';
							if (record.obj)
								obj = record.obj;
							var doc = '';
							if (record.doc)
								doc = record.doc;
							var docstr = '';
							if (record.docstr)
								docstr = record.docstr;
							var objstr = '';
							if (record.objstr)
								objstr = ' (' + record.objstr + ')';
							var icons = '';
							if (record.digitized)
								icons += '<img class="icon" src="../images/icon-magnify.gif"/>';
							if (record.metadata)
								icons += '<img class="icon" src="../images/icon-description.gif"/>';
	
							if (options.browsetype == 'browseminiature' || 
							    options.browsetype == 'browseminiaturekeyword' || 
							    options.browsetype == 'browsebiblebks') {
								var page = '/view/index.php';
							} else {
								var page = '/ms/index.php';
                            }
                            var href1 = '<a href="' + page + '?doc=' + doc + '&obj=' + obj + '">';
                            var href2 = '</a>';
		
							html += '<li>' + href1 + docstr + objstr + icons + href2 + '</li>';
						});
						html += '</ul>';
					});
 				});

				$(container).append(html);

				$(container).append("<h2>Key</h2><p>Click on an entry to see what resources are available for this manuscript.</p><ul><li><img style='padding-right: 6px;' src='../images/icon-description.gif'/> A metadata record is available for this manuscript.</li><li><img style='padding-right: 6px;' src='../images/icon-magnify.gif'/>Page images are available for this manuscript.</li></ul>");

				// hide ul.expandable's, and set up onclick.
				$('.expandable').each(function() {
					$(this).hide().parent().find('a:first').click(function() {
						var li = $(this).parent();
						var ul = li.find('ul:first');

						ul.toggle();

						if (ul.is(':hidden'))
							li.removeClass('expanded').addClass('collapsed');
						else
							li.removeClass('collapsed').addClass('expanded');

						return false;
					});
				});
			});
		}
	});	
})(jQuery);
