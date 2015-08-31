<?php

// import config
$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Klass"] = 0; // Klasser
$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["ElevID"] = 1; // ID (personnummer)
$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Efternamn"] = 2; // Elevens efternamn
$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Fornamn"] = 3; // Elevens förnamn
$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Larare"] = 4; // Lärare (Mentor)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["GruppID"] = 1; // ID (Grupper/kurser)
$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Larare"] = 2; // Undervisande lärare (kommaseparerade lärar-taggar)
$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Elever"] = 3; // Elever (kommaseparerade personnummer)
$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Period"] = 0; // termin/hela året
$IMPORT["Rapporter"]["Grupper"]["AllowedPrefixes"] = array(
"AG15", // språkval
"15FM/AG/", // 14FM//Em etc ind val
"15FM//AG/", // fix för skrivfel
"15EM/AG/",
"15FMHT/AG/",
"15FMVT/AG/",
"AG16", // språkval
"16FM/AG/", // 14FM//Em etc ind val
"16EM/AG/",
"16FMHT/AG/",
"16FMVT/AG/",
"FNA",
"IB",
"NA",
"TE",
"TIME"
); 
$IMPORT["Rapporter"]["Grupper"]["DisallowedPostfixes"] = array(
":a",
":b"
);

$IMPORT["Fil"]["ColumnSeparator"] = ";"; // Separerar fält/kolumner i importfilen
$IMPORT["Fil"]["ValueSeparator"] = ","; // Separerar värden i ett fält/kolumn i de fall de innehåller flera värden
$IMPORT["Fil"]["FirstLineIsColumnnames"] = true; 
$IMPORT["Fil"]["PreivewData"] = true; 
$IMPORT["Fil"]["NumPreviewLines"] = 2; 
$IMPORT["Fil"]["MaxFileSize"] = 500000; 
$IMPORT["Fil"]["AllowedExts"] = array("csv", "txt"); 

$IMPORT["outputLog"] = true; 

?>