<?php include("include_form_modals.php"); ?>

<script type="text/javascript">
	function checkForm(mode){
		//alert("Kontrollera form. mode = '"+mode+"'");
		
		var success = true;
		var msg = "";


		//if(mode == "save"){
			
			if($(".form-group #titel").val() == ""){
				msg += "<p>Du måste anges en <strong>boktitel</strong></p>";
				success = false;
			}

			if($(".form-group #isbn").val() == ""){
				msg += "<p>Du måste anges ett <strong>ISBN-nummer</strong></p>";
				success = false;
			}

			if($(".form-group #antal").val() == ""){
				msg += "<p>Du måste anges ett <strong>antal</strong> för bokbeståndet</p>";
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
		var theForm = document.getElementById("form-bocker");
		$("#form-mode").val(mode); // sätter form-fält till mode som page_bok använder
		theForm.submit();
	}
</script>
