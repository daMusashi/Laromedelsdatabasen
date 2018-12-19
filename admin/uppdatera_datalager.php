<?php
    require_once("class_datalager.php");
?>

<h1>Uppdaterar alla datalager</h1>

<?php

$time = 0;
$time_total = 0;

print "<p>Skapar lager för kursdata</p>";
$time = Datalager::createKursData();
print "<p>....klart på $time s</p>";
$time_total += $time;

print "<p>Skapar lager för bokningsdata</p>";
$time = Datalager::createBokningsData();
print "<p>....klart på $time s</p>";
$time_total += $time;

print "<p>Skapar lager för elevboksdata</p>";
$time = Datalager::createElevboksData();
print "<p>....klart på $time s</p>";
$time_total += $time;

print "<p>ALLT färdigt, på $time_total s</p>";
