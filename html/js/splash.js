function showSplash(url, caller) {
	// create xmlhttp object
	xmlhttp = createXMLHttp();
	// set the callback
	xmlhttp.onreadystatechange = function() {
		// check xmlhttp ready state
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			alert(xmlhttp.responseText);
			//document.getElementById(caller).innerHTML = xmlhttp.responseText;
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
