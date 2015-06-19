$(document).ready(function() 
    { 

    } 
);

$( document ).ajaxError(function() {
  mnnDebug("Document: Global felhanterare Ajax", "Ett Ajax-anrop failade :(");
});

function addMessage(content, error){
	$("#messages").append("<p>" + content + "</p>");	
}

function mnnDebug(source, txt){
	console.log("DEBUG läromodel [" + source + "]: " + txt);	
}



function cleanString(str){
	return str.replace(/(\r\n|\n|\r)/gm,"");	
}

