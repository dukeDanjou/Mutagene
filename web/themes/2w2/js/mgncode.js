function insertTag(startTag, endTag, textareaId, tagType) {
	var field = document.getElementById(textareaId);
	var scroll = field.scrollTop;
	field.focus();
	
	
	if (window.ActiveXObject) {
		var textRange = document.selection.createRange();            
		var currentSelection = textRange.text;
	} else {
		var startSelection   = field.value.substring(0, field.selectionStart);
		var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
		var endSelection     = field.value.substring(field.selectionEnd);
	}
	
	if (tagType) {
		switch (tagType) {
			case "lien":
					endTag = "[/lien]";
					if (currentSelection) {
							if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0) {
									var label = prompt("Quel est le libell� du lien ?") || "";
									startTag = "[lien url=" + currentSelection + "]";
									currentSelection = label;
							} else {
									var URL = prompt("Quelle est l'url ?");
									startTag = "[lien url=" + URL + "]";
							}
					} else {
							var URL = prompt("Quelle est l'url ?") || "";
							var label = prompt("Quel est le libellé du lien ?") || "";
							startTag = "[lien url=" + URL + "]";
							currentSelection = label;                     
					}
			break;
			case "citation":
					endTag = "[/citation]";
					if (currentSelection) {
							if (currentSelection.length > 30) {
									var auteur = prompt("Quel est l'auteur de la citation ?") || "";
									startTag = "[citation nom=\"" + auteur + "\"]";
							} else {
									var citation = prompt("Quelle est la citation ?") || "";
									startTag = "[citation nom=\"" + currentSelection + "\"]";
									currentSelection = citation;    
							}
					} else {
							var auteur = prompt("Quel est l'auteur de la citation ?") || "";
							var citation = prompt("Quelle est la citation ?") || "";
							startTag = "[citation nom=\"" + auteur + "\"]";
							currentSelection = citation;    
					}
			break;	
		}
	}
	
	if (window.ActiveXObject) {
		textRange.text = startTag + currentSelection + endTag;
		textRange.moveStart('character', -endTag.length-currentSelection.length);
		textRange.moveEnd('character', -endTag.length);
		textRange.select();  
	} else { // Ce n'est pas IE
		field.value = startSelection + startTag + currentSelection + endTag + endSelection;
		field.focus();
		field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
	}  
	
	field.scrollTop = scroll;   
}

function getXMLHttpRequest() {
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest();
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	
	return xhr;
}


function view(textareaId, type, viewDiv, url){
	var content = encodeURIComponent(document.getElementById(textareaId).value);
	var xhr = getXMLHttpRequest();
	
	if (xhr && xhr.readyState != 0) {
		xhr.abort();
		delete xhr;
	}
	
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200){
			document.getElementById(viewDiv).innerHTML = xhr.responseText;
		} else if (xhr.readyState == 3){
			document.getElementById(viewDiv).innerHTML = "<div style=\"text-align: center;\">Chargement en cours...</div>";
		}
	}
	
	xhr.open("POST", url + "/core/view/" + type, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("string=" + content);

	alert("Data envoye :"+data);
}