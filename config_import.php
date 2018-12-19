<?php



// import config

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Klass"] = 3; // Klasser

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["ElevID"] = 0; // ID (personnummer)

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Efternamn"] = 1; // Elevens efternamn

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Fornamn"] = 2; // Elevens förnamn

$IMPORT["Rapporter"]["Elever"]["FieldIndex"]["Larare"] = 4; // Lärare (Mentor)



$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["GruppID"] = 0; // ID (Grupper/kurser)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Larare"] = 1; // Undervisande lärare (kommaseparerade lärar-taggar)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Elever"] = 2; // Elever (kommaseparerade personnummer)

$IMPORT["Rapporter"]["Grupper"]["FieldIndex"]["Period"] = 3; // termin/hela året

$IMPORT["Rapporter"]["Grupper"]["AllowedPrefixes"] = array(

"18FM/AG/", // 14FM//Em etc ind val

"18FM//AG/", // fix för skrivfel

"18EM/AG/",

"18FMHT/AG/",

"18FMVT/AG/",

"AG18", // språkval

"SAG18",

"19FM/AG/", // 14FM//Em etc ind val

"19FM//AG/", // fix för skrivfel

"19EM/AG/",

"19FMHT/AG/",

"19FMVT/AG/",

"AG19", // språkval

"SAG19",

"FNA",

"IB",

"NA",

"TE",

"TIME",

"IMSPR",

"SVA"

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