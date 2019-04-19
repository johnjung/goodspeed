if (!JEJ) 
	var JEJ = {};
if (!JEJ.THUMBS)
	JEJ.THUMBS = {};

/*
 * avoid accidentally overwriting window.onloads
 */

JEJ.makeDoubleDelegate = function(f1, f2) {
	return function() {
		if (f1) {
			f1();
		}
		if (f2) {
			f2();
		}
	};
}

JEJ.THUMBS.obj = 100;

JEJ.THUMBS.loader = function() {
	var i = document.getElementById('0229' + JEJ.THUMBS.obj);
	i.src = 'http://goodspeed.lib.uchicago.edu/images/thumbs/gms-0229-'
+ JEJ.THUMBS.obj + '.jpg';
	if (JEJ.THUMBS.obj < 500) {
		JEJ.THUMBS.obj++;
		setTimeout(JEJ.THUMBS.loader, 15);
	}
}
window.onload = JEJ.makeDoubleDelegate(window.onload, JEJ.THUMBS.loader);

