<?php include("include_form_modals.php"); ?>

<script type="text/javascript">
	function checkForm(mode){
		//alert("Kontrollera form. mode = '"+mode+"'");
		
		var success = true;
		var msg = "";


		//if(mode == "save"){
			
			if($("#select-bok").val() == "null"){
				msg += "<p>Du måste anges en <strong>bok</strong></p>";
				success = false;
			}

			if($("#select-kurs").val() == "null"){
				msg += "<p>Du måste anges en <strong>kurs</strong></p>";
				success = false;
			}

			if($("#select-larare").val() == "null"){
				msg += "<p>Du måste anges en <strong>bokningslärare</strong></p>";
				success = false;
			}
		//} 

		if(success) {
			submitForm(mode);
		} else {
			$("#validate-error-modal-titel").text("Bokningen kunde inte skapas - värden saknas!");
			$("#validate-error-modal-body").html(msg);
			$('#validate-error-modal').modal();
		}
	}
	

	function submitForm(mode){
		//alert("Skickar form. mode = '"+mode+"'");
		var theForm = document.getElementById("form-bokning");
		$("#form-mode").val(mode); // sätter form-fält till mode som page_bok använder
		theForm.submit();
	}
</script>
