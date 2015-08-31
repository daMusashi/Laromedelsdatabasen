
<?php require_once("class_html_factory_print.php"); ?>

<div class="page-header">
	<h1>Skriv ut litteraturlistor</h1>
</div>
<p>Välj typ av litteraturlista att skriva ut</p>

<script>
	$(document).ready(function(){
		$('#print-tabs a').click(function (e) {
	  		e.preventDefault();
	  		$(this).tab('show');
		});
	});
</script>

<div role="tabpanel">

	<ul id="print-tabs" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#tab-elever-klass" aria-controls="tab-elever-klass" role="tab" data-toggle="tab">Elever - klassvis</a></li>
		<li><a href="#tab-elever" aria-controls="tab-elever" role="tab" data-toggle="tab">Elever - individuellt</a></li>
	    <?php if(isAdmin()){ ?>
	    	<li><a href="#tab-klasser" aria-controls="tab-klasser" role="tab" data-toggle="tab">Klasser</a></li>
	    <?php } ?>
	    <li><a href="#tab-kurser" aria-controls="tab-kurser" role="tab" data-toggle="tab">Kurser</a></li>
	    
	</ul>


	<div class="tab-content">

		<div role="tabpanel" id="tab-elever-klass"  class="tab-pane active">
			<h2>Elever - alla litteraturlistor för en klass</h2>
			<p>Skriver ut en litteraturlista för <strong>varje elev i en klass</strong></p>
			<div class="print-form" id="print-form-elever-klass">
				<?php print HTML_FACTORY_PRINT::getElevklassAjaxSelect(); ?> 
			</div>
		</div>
		<div role="tabpanel" id="tab-elever"  class="tab-pane">
			<h2>Elever - enskilda litteraturlistor</h2>
			<p>Skriver ut en <strong>enskild</strong> litteraturlista för en elev</p>
			<div class="print-form" id="print-form-elever">
				<?php print HTML_FACTORY_PRINT::getElevIndAjaxSelect(); ?> 
			</div>
		</div>
		<div role="tabpanel" id="tab-kurser" class="tab-pane">
			<h2>Kurser</h2>
			<p>Skriver ut en litteraturlista för en <strong>kurs</strong></p>
			<div class="print-form">
				<?php print HTML_FACTORY_PRINT::getKursAjaxSelect(); ?> 
			</div>
		</div>

		<div role="tabpanel" id="tab-klasser" class="tab-pane">
			<h2>Klasser</h2>
			<p>Skriver ut en litteraturlista för en <strong>klass</strong> (inga elever angivna)</p>
			<p class="alert alert-warning"><strong>OBSERVERA</strong> antalet böcker anger <strong>inte</strong> antalet böcker i klassen, utan antalet böcker i <strong>kursen</strong> som en eller flera elever från klassen deltar i (och boken är bokad för)</p>
			<div class="print-form">
				<?php print HTML_FACTORY_PRINT::getKlassAjaxSelect(); ?> 
		    </div>
		</div>

		

	</div>

</div>

<script>
function printData(domIdToPrint){
	var content = document.getElementById(domIdToPrint);
	var printContent = content.cloneNode(true);
	
	var script = document.createElement("script");
	script.innerHTML = "window.print();";
	printContent.appendChild(script);

	var html = "<html><head>";
		html += "<title>UTSKRIFT - Läromedelsbokning</title>";
		html += "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bootstrap.css\">";
		html += "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/print.css\">";
		html += "</head>";
	html += "<body>" + printContent.innerHTML + "</body></html>";

	var printWin = window.open("", "", "width=800, height=800");
	
	printWin.document.write(html);
	//printWin.print();
}
</script>

