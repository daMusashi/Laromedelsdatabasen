<a tabindex="0" id="notis-notis" role="button" class="btn btn-info" data-toggle="popover" data-placement="bottom">
<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
Notis 23/8
</a>

<span>&nbsp;&nbsp;&nbsp;</span>

<a tabindex="1" id="notis-overbooked" role="button" class="btn btn-danger" data-container="body" data-toggle="popover" data-placement="bottom">
<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
Överbokningar
</a>

<?php

$notis = '<p><strong>Alla elever och kurser borde vara med</strong>, så hör av dig om det inte stämmer (martin.nilsson@karlstad.se)</p>';
$notis .= '<p>Allt är klart för utskrifter av <strong>litteraturlistor</strong>. På vissa elevers litteraturlistor kan samma bok förekomma två gånger (till två olika kurser). Eleven ska dock förstås bara hämta ut ett exemplar.</p>';

$overbooked = '<p>Det finns ett antal markerade <strong>överbokningar</strong> i bokningslistan</p>';
$overbooked .= '<p>Kontrollera om de gäller någon av dina bokningar</p>';
$overbooked .= '<p>Om så, kontrollera om det faktiskt är en överbokning, eller beror på andra orsaker såsom:</p>';
    $overbooked .= '<ul>';
        $overbooked .= '<li>Felaktiga angivet bokantal i depån</li>';
        $overbooked .= '<li>Samma elev använder samma bok i flera kurser (och boken då är bokad 2ggr fast bara 1 praktiken behövs)</li>';
        $overbooked .= '<li>Bokningslistan sammanfattar antalet bokade böcker för ett helår mot antalet böcker i depån, men böcker kan växla elever till våren (två enterminskurser efter varandra)</li>';
    $overbooked .= '</ul>';

?>


<script>
$(document).ready(function(){
    $('#notis-notis').popover({html: true, content: '<?php print $notis; ?>', title: "Notiser"});
    $('#notis-overbooked').popover({html: true, content: '<?php print $overbooked; ?>', title: "Överbokningar"});
});
</script>

<p></p>