function showSplash(url, caller) {
	// create xmlhttp object
	xmlhttp = createXMLHttp();
	// set the callback
	xmlhttp.onreadystatechange = function() {
		// check xmlhttp ready state
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			splash = document.getElementById(caller); 
			splash.innerHTML = xmlhttp.responseText;			
			var x = (document.body.clientWidth - splash.clientWidth) / 2 + 'px';
			splash.style.visibility="visible";
			splash.style.left = x;
		}
	};
	// prepare request
	xmlhttp.open("GET", url, true);
	// send request
	xmlhttp.send();
}

function createXMLHttp() {
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest(); // IE7+, Firefox, Chrome, Opera, Safari
	} else {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // IEs
	}
	return xmlhttp;
}
