<?php global $CONFIG ?>
<script type="text/javascript">
	$(document).ready(function(){
		// Lägger inits i egen funktion då de senare behöver återinitieras (se nedan)
		setUpTabsAndButtons();
		
	});
	
	// Lägger dessa inits i egen funktion då dessa även måste köras efter utskrift då innehållet läggs tillbaka (då load ovan redan "passerat")
	function setUpTabsAndButtons(){
		$("#tabs").tabs();
		
		$("#select-kurs").change(function(){
			kurs = $(this).val();
			if(kurs != "null"){
				q = "<?php print $CONFIG["ajaxParam"] ?>=print&<?php print $CONFIG["primNavParam"]?>=kurs&<?php print $CONFIG["refIdParam"] ?>="+kurs;
				$.get("ajax.php?"+q)
  					.always(function(data){
						mnnDebug("Rapporter: Select kurs, ajax-data", data);
					})
					.done(function(data){
						mnnDebug("Rapporter: Select kurs, ajax-data", "DONE!");
						$("#kurs-print-data").html( data );
					})
					.fail(function(){
						mnnDebug("Rapporter: Select kurs, ajax-data", "FAILED! :((");
					});
			}
		});
		
		$("#select-klass").change(function(){
			klass = $(this).val();
			if(klass != "null"){
				q = "<?php print $CONFIG["ajaxParam"] ?>=print&<?php print $CONFIG["primNavParam"]?>=klass&<?php print $CONFIG["refIdParam"] ?>="+klass;
				$.get("ajax.php?"+q)
  					.always(function(data){
						mnnDebug("Rapporter: Select klass, ajax-data", data);
					})
					.done(function(data){
						mnnDebug("Rapporter: Select klass, ajax-data", "DONE!");
						$("#klass-print-data").html( data );
					})
					.fail(function(){
						mnnDebug("Rapporter: Selectklass, ajax-data", "FAILED! :((");
					});
			}
		});
		
		$("#select-klass-elev").change(function(){
			klass = $(this).val();
			if(klass != "null"){
				q = "<?php print $CONFIG["ajaxParam"] ?>=print&<?php print $CONFIG["primNavParam"]?>=elev-select&<?php print $CONFIG["refIdParam"] ?>="+klass;
				$.get("ajax.php?"+q)
  					.always(function(data){
						mnnDebug("Rapporter: Select klass för elever, ajax-data", data);
					})
					.done(function(data){
						mnnDebug("Rapporter: Select klass för elever, ajax-data", "DONE!");
						$("#elev-print-data").html("");
						if($("#select-elev").length){
							$("#print-form-elever .field-container").last().remove();
						}
						$("#print-form-elever").append(data);
						$("#select-elev").change(function(){
							elev = $(this).val();
							if(elev != "null"){
								q = "<?php print $CONFIG["ajaxParam"] ?>=print&<?php print $CONFIG["primNavParam"]?>=elev&<?php print $CONFIG["refIdParam"] ?>="+elev;
								$.get("ajax.php?"+q)
  									.always(function(data){
										mnnDebug("Rapporter: Select elev, ajax-data", data);
									})
									.done(function(data){
										mnnDebug("Rapporter: Select elev, ajax-data", "DONE!");
										$("#elev-print-data").html(data);
									})
									.fail(function(){
										mnnDebug("Rapporter: Select elev, ajax-data", "FAILED! :((");
									});
							}
						});
					})
					.fail(function(){
						mnnDebug("Rapporter: Select klass för elever, ajax-data", "FAILED! :((");
					});
			} else {
				if($("#select-elev").length){
					$("#print-form-elever .field-container").last().remove();
				}
				$("#elev-print-data").html("");
			}
		});
	}
	
	function printElement(elementId) {
     	var printElement = document.getElementById(elementId).innerHTML;
	 	mnnDebug("printElement", "print elem: " + elementId);
	 	mnnDebug("printElement", "print elem contents: " + printElement);
     	var originalBody = document.body.innerHTML;

     	document.body.innerHTML = printElement;

     	window.print();

     	document.body.innerHTML = originalBody;
	 	setUpTabsAndButtons();
	}
	
</script>
<h1>Skriv ut litteraturlistor</h1>
<p>Välj typ av litteraturlista att skriva ut</p>

<div id="tabs" class="print-box">
<ul>
	<li><a href="#tab-kurser">Kurser</a></li>
    <li><a href="#tab-klasser">Klasser</a></li>
    <li><a href="#tab-elever">Elever</a></li>
</ul>

<div id="tab-kurser" class="tab">
	<h2>Kurser</h2>
	<p>Skriver ut en litteraturlista för en <strong>kurs</strong></p>
	<div class="print-form">
		<?php print getSelectKursWithBokningarHTML("Bara kurser med bokningar är valbara."); ?> 
	</div>
    <div id="kurs-print-data"  class="print-elem"></div>
</div>

<div id="tab-klasser" class="tab">
	<h2>Klasser</h2>
	<p>Skriver ut en litteraturlista för en <strong>klass</strong> (inga elever angivna)</p>
	<div class="print-form">
		<?php print getSelectKlassWithBokningarHTML("Bara klasser med bokningar är valbara."); ?>
    </div>
    <div id="klass-print-data" class="print-elem"></div>
</div>

<div id="tab-elever">
	<h2>Elever</h2>
	<p>Skriver ut en litteraturlista för en <strong>elev</strong></p>
	<div class="print-form" id="print-form-elever">
		<?php print getSelectKlassWithBokningarHTML("Välj klass. Bara klasser med bokningar är valbara.", "", "select-klass-elev"); ?>
	</div>
    <div id="elev-print-data" class="print-elem"></div>
</div>

</div>