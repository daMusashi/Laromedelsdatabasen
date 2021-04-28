<?php global $CONFIG ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#tabs").tabs();
	});
</script><h1>Hjälp & instruktioner</h1>

<div id="tabs" class="print-box help">
<ul>
	<li><a href="#tab-kurser">Göra bokningar</a></li>
    <li><a href="#tab-klasser">Kurskoder</a></li>
    <li><a href="#tab-elever">Kontakt för ytterligare hjälp</a></li>
</ul>

<div id="tab-kurser" class="tab">
	<h2>Så här gör du en bokning</h2>
	<div class="help-bokning" id="help-bokning-1">
   	  <p>Du kan bara boka <strong>en bok åt gången</strong>, och du kan inte boka samma  boka till samma kurs flera gånger. Du gör en bokning i <strong>bokningsformuläret</strong>. Du kan öppna bokningsformuläret på flera sätt, se nedan.</p>
   	  <h3>Öppna bokningsformuläret</h3>
        <p>Du kan öppna bokningsformuläret på flera sätt</p>
        <ul>
          <li>I <strong>boklistan</strong> (&quot;Böcker&quot;-fliken<a href="http://lampan.karlstad.se/~laromedel/?a=bocker"></a>), klicka på <strong>boka-knappen</strong> bredvid en bok du vill boka. Vald bok kommer att vara förvald i bokningsformuläret.</li>
          <li>I <strong>kurslistan</strong> (&quot;Kurser&quot;-fliken), klicka på <strong>boka-knappen</strong> bredvid en kurs du vill boka en bok till. Vald kurs kommer att vara förvald i bokningsformuläret.</li>
          <li>Överst i <strong>bokningslistan</strong> (&quot;Bokningar&quot;-fliken), klicka på <strong>knappen &quot;Gör en bokning&quot;</strong>. Bokningsformuläret visas utan några förval.</li>
        </ul>
    </div>
    <div class="help-bokning" id="help-bokning-2">
   	  <h3>Fylla i bokningsformuläret</h3>
        <p>Följande information behöver du fylla i och spara för bokningen. <strong>All information utom kommentar är obligatorisk för att bokningen ska kunna sparas</strong>.</p>
        <ul>
          <li><strong>Bok</strong>. Välj boken du vill boka i listan. <strong>OBS</strong> har du startat boknngen från en bok i boklistan är boken redan förvald.</li>
          <li><strong>Kurs</strong>. Välj kursen du vill boka boken till. Kurserna beskrivs med <strong>kurskoder</strong>, se mer om dom i <strong>nästa hjälp-flik</strong> (fliken &quot;Kurskoder&quot;). <strong>OBS</strong> har du startat boknngen från en kurs i bokkurs är kursen redan förvald.</li>
          <li><strong>Bokningslärare</strong>. Ange vem du är; läraren som gör bokningen. <strong>OBS</strong> Vilken lärare som <strong>undervisar</strong> i kursen ska <strong>inte</strong> anges här, utan bokningsläraren (dessa kan dock vara samma person förstås). Detta för att biblioteket ska veta vem man ska vända sig till med frågor kring bokningen.</li>
          <li><strong>Kommentar</strong>. Om du som bokare vill spara med extrainformation som läromedelsutlåningen behöver veta, skriv den här. Den här information är den enda man kan låta bli att fylla i.</li>
        </ul>
        <p>När informationen ovan är ifylld <strong>sparar du bokningen</strong> genom att klicka på <strong>knappen &quot;Boka&quot;</strong>.</p>
    </div>
    <div class="help-bokning" id="help-bokning-3">
   	  <h3>Kontrollera bokningen</h3>
        <p>När bokningen är sparats, efter att du tryckt på boka-knappen, så kommer den sparade informationen för bokningen att visas. </p>
        <p>Kontrollera att informationen stämmer. Om den inte gör det kontakta biblioteket, annars är bokningen klar och du kan påbörja en ny bokningen om du har fler bokningar att göra.</p>
    </div>
</div>

<div id="tab-klasser" class="tab">
	<h2>Kurskoder</h2>
	<p>Kurserna beskrivs i läromedelsbokningen med en kurskod. Samma kurskod ger namn åt kursen i It's Learning. Koden är uppbyggd enligt följande:</p>
	<p><strong>Undervisningsgrupp/Skolverkets kurskod</strong></p>
	<p>Om en undervisninggrupp t.ex. är <strong>klassen TIME13</strong>, och kursen de ska undervisas i är Matematik 2a, som <strong>Skolverket givit kurskoden MATMAT02a</strong>, så bli <strong>Älvkullens kurskod</strong>:</p>
	<p><strong>TIME13/MATMAT02a</strong></p>
	<p>Det finns andra undervisningsgrupper än klasser, t.ex. för profilämnen på Teknik och individuella val. De följer ett logiskt namngivningsystem. <strong>Om du är osäker på Älvkullens kursko</strong>d på en kurs du ska boka, <strong>kontakta expeditionen, eller din rektor</strong>. Sker bokningen under pågående läsår, så<strong> förekommer kurskoden</strong> både i <strong>ditt schema</strong> och i<strong> kursnamnen på It's Learning</strong>.</p>
	<p>Du kan ta reda på Skolverkets kurskoder, där kurserna beskrivs i respektve ämnes ämnesplaner på <a href="http://www.skolverket.se/laroplaner-amnen-och-kurser/gymnasieutbildning/gymnasieskola/sok-amnen-kurser-och-program" target="_blank">Skolverkets webbplats</a>.</p>
	<p>&nbsp;</p>
</div>

<div id="tab-elever">
	<h2>Kontakt för ytterligare hjälp</h2>
	<h3>Användning och ändring av uppgifter (böcker och bokningar)</h3>
	<p>Hellen Andersson, Biblioteket</p>
	<h3>Fel i applikationen, förslag på ändringar</h3>
	<p>Martin Nilsson, TIME, 0704-64 64 23 (privat), martin.nilsson@karlstad.se</p>
</div>

</div>