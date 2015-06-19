
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
		<li class="active"><a href="#tab-kurser" aria-controls="tab-kurser" role="tab" data-toggle="tab">Kurser</a></li>
	    <li><a href="#tab-klasser" aria-controls="tab-klasser" role="tab" data-toggle="tab">Klasser</a></li>
	    <li><a href="#tab-elever" aria-controls="tab-elever" role="tab" data-toggle="tab">Elever</a></li>
	</ul>


	<div class="tab-content">

		<div role="tabpanel" id="tab-kurser" class="tab-pane active">
			<h2>Kurser</h2>
			<p>Skriver ut en litteraturlista för en <strong>kurs</strong></p>
			<div class="print-form">
				<?php HTML_FACTORY::getSelectKursWithBokningarHTML("Bara kurser med bokningar är valbara."); ?> 
			</div>
		    <div id="kurs-print-data"  class="print-elem"></div>
		</div>

		<div role="tabpanel" id="tab-klasser" class="tab-pane">
			<h2>Klasser</h2>
			<p>Skriver ut en litteraturlista för en <strong>klass</strong> (inga elever angivna)</p>
			<div class="print-form">
				<?php //print getSelectKlassWithBokningarHTML("Bara klasser med bokningar är valbara."); ?>
		    </div>
		    <div id="klass-print-data" class="print-elem"></div>
		</div>

		<div role="tabpanel" id="tab-elever"  class="tab-pane">
			<h2>Elever</h2>
			<p>Skriver ut en litteraturlista för en <strong>elev</strong></p>
			<div class="print-form" id="print-form-elever">
				<?php //print getSelectKlassWithBokningarHTML("Välj klass. Bara klasser med bokningar är valbara.", "", "select-klass-elev"); ?>
			</div>
		    <div id="elev-print-data" class="print-elem"></div>
		</div>

	</div>

</div>

