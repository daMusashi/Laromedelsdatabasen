<?php



// import config
// personnummer 0;Efternamn 1;Ffrnamn 2;Ansvarig lärare 3;Klass 4: [ n*, te1* ] 5;Grupp 6

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Klass"] = 3; // Klasser

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["ElevID"] = 0; // ID (personnummer)

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Efternamn"] = 1; // Elevens efternamn

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Fornamn"] = 2; // Elevens förnamn

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Larare"] = 4; // Lärare (Mentor)


// ID (grupp) 0;Undervisande lärare 1;ht,vt 2;Elever 3
$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["GruppID"] = 0; // ID (Grupper/kurser)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Larare"] = 1; // Undervisande lärare (kommaseparerade lärar-taggar)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Elever"] = 2; // Elever (kommaseparerade personnummer)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Period"] = 3; // termin/hela året

$IMPORT["Rapporter"]["Grupper"]["AllowedPrefixes"] = array(

"20FM", // FIX som tar alla då 'ä' i SÄG verkar faila
"20EM", // FIX som tar alla då 'ä' i SÄG verkar faila
"21FM", // FIX som tar alla då 'ä' i SÄG verkar faila
"21EM", // FIX som tar alla då 'ä' i SÄG verkar faila

"20FM/SÄG/", // 14FM//Em etc ind val

"20EM/SÄG/",

"20FMHT/SÄG/",

"20FMVT/SÄG/",

"AG20", // språkval
"SÄG20", // språkval
"SAG20", // språkval

"21FM/SÄG/", // 14FM//Em etc ind val

"21EM/SÄG/",

"21FMHT/SÄG/",

"21FMVT/SÄG/",

"AG21", // språkval

"SAG21", // språkval
"SÄG21", // språkval

"FNA",

"IB",

"NA",

"TE",

"IMSPR",

"SVA",

"IM"

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
