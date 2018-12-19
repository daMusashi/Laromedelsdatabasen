function checkForm(mode){
	mnnDebug("bocker-checkForm", "Kontrollera form");
	
	if(mode == "delete"){
		mnnDebug("bocker-checkForm", "Delete-läge: skapar bekräftelse");
		dialogConfirmation("Bekräfta", "Vill du verkligen <strong>RADERA</strong> boken?", "sendDelete();")
	} else {
		mnnDebug("bocker-checkForm", "Startar submit");
		submitForm(mode);
	}
}
	
function sendDelete(){
	<?php $send = "?" . Config::PARAM_PRIM_NAV . "=bocker&" . Config::PARAM_SEC_NAV . "=delete&" . Config::PARAM_REF_ID . "=" . $bok->isbn; ?>	
	window.location.href = '<?php print $send; ?>';	
}
	
function submitForm(mode){
	mnnDebug("bocker-submitForm", "Skickar form");
	var theForm = document.getElementById("form-bocker");
	$("#form-mode").val(mode);
	theForm.submit();
}