var xmlhttp;

function getXmlHTTP () {
	
	var browser;
	
		try {
			//Javascript in IE above version 5
			browser = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
			try {
			//Javascript in IE below version 5
			browser = new ActiveXObject("Microsoft.XMLHTTP");
			} catch(E) {
				browser = false;
			}
		}
		
		//Not using a IE-Browser
		if (!browser && typeof XMLHttpRequest != 'undefined') {
			browser = new XMLHttpRequest();
		}
	
	return browser;	
	
}
	
function grabPage(serverPage, objID) {
	
	
	//Define the XML-HTTP Object
	xmlhttp = getXmlHTTP();
		
	var obj = document.getElementById(objID);
	 xmlhttp.open("GET", serverPage, true);
	 xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			 obj.innerHTML = xmlhttp.responseText;
			
			 
		}
	 }
 xmlhttp.send(null);
}