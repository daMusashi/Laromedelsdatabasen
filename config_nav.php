<?php
// Navigering


$NAV["kurser"]["include"] = "page_kurser.php";
$NAV["kurser"]["label"] = "Kurser";
$NAV["kurser"]["roll"] = "all";
$NAV["kurser"]["place"] = "main";

$NAV["bocker"]["include"] = "page_bocker_lista.php";
$NAV["bocker"]["label"] = "Böcker";
$NAV["bocker"]["roll"] = "all";
$NAV["bocker"]["place"] = "main";

$NAV["bokningar"]["include"] = "page_bokningar_lista.php";
$NAV["bokningar"]["label"] = "Bokningar";
$NAV["bokningar"]["roll"] = "user";
$NAV["bokningar"]["place"] = "main";

$NAV["print"]["include"] = "page_print.php";
$NAV["print"]["label"] = "Skriv ut";
$NAV["print"]["roll"] = "user";
$NAV["print"]["place"] = "main";

$NAV["bok"]["include"] = "page_bok.php";
$NAV["bok"]["label"] = "<hidden>";
$NAV["bok"]["roll"] = "admin";

$NAV["admin"]["include"] = "admin/admin.php";
$NAV["admin"]["label"] = "<hidden>";
$NAV["admin"]["roll"] = "admin";


/*$NAV["admin"]["include"] = "admin/admin.php";
$NAV["admin"]["label"] = "Administration";
$NAV["admin"]["roll"] = "admin";
$NAV["admin"]["place"] = "admin";*/

/*$NAV["dev"]["include"] = "admin/admin.php";
$NAV["dev"]["label"] = "Utveckling";
$NAV["dev"]["roll"] = "dev";
$NAV["dev"]["place"] = "dev";*/

$NAV["help"]["include"] = "page_help.php";
$NAV["help"]["label"] = "Hjälp";
$NAV["help"]["roll"] = "user";
$NAV["help"]["place"] = "main";

// underval/sekundär eny

$NAV["bocker-view"]["include"] = "page_bocker_bok.php";
$NAV["bocker-view"]["label"] = "<hidden>";
$NAV["bocker-view"]["roll"] = "all";

$NAV["bocker-add"]["include"] = "page_bocker_bok.php";
$NAV["bocker-add"]["label"] = "<hidden>";
$NAV["bocker-add"]["roll"] = "admin";

$NAV["bocker-edit"]["include"] = "page_bocker_bok.php";
$NAV["bocker-edit"]["label"] = "<hidden>";
$NAV["bocker-edit"]["roll"] = "admin";

$NAV["bocker-save"]["include"] = "page_bocker_bok.php";
$NAV["bocker-save"]["label"] = "<hidden>";
$NAV["bocker-save"]["roll"] = "admin";

$NAV["bocker-delete"]["include"] = "page_bocker_bok.php";
$NAV["bocker-delete"]["label"] = "<hidden>";
$NAV["bocker-delete"]["roll"] = "admin";

$NAV["bokningar-add"]["include"] = "page_bokningar_bokning.php";
$NAV["bokningar-add"]["label"] = "<hidden>";
$NAV["bokningar-add"]["roll"] = "user";

$NAV["bokningar-view"]["include"] = "page_bokningar_bokning.php";
$NAV["bokningar-view"]["label"] = "<hidden>";
$NAV["bokningar-view"]["roll"] = "user";

$NAV["bokningar-edit"]["include"] = "page_bokningar_bokning.php";
$NAV["bokningar-edit"]["label"] = "<hidden>";
$NAV["bokningar-edit"]["roll"] = "admin";

$NAV["bokningar-save"]["include"] = "page_bokningar_bokning.php";
$NAV["bokningar-save"]["label"] = "<hidden>";
$NAV["bokningar-save"]["roll"] = "user";

$NAV["bokningar-delete"]["include"] = "page_bokningar_bokning.php";
$NAV["bokningar-delete"]["label"] = "<hidden>";
$NAV["bokningar-delete"]["roll"] = "admin";

$NAV["admin-import"]["include"] = "admin/import.php";
$NAV["admin-import"]["label"] = "Import";
$NAV["admin-import"]["roll"] = "dev";
$NAV["admin-import"]["place"] = "admin";

$NAV["admin-tabeller"]["include"] = "admin/printTabeller.php";
$NAV["admin-tabeller"]["label"] = "Tabeller";
$NAV["admin-tabeller"]["roll"] = "dev";
$NAV["admin-tabeller"]["place"] = "admin";

$NAV["admin-phpmyadmin"]["include"] = "admin/admin_phpmyadmin.php";
$NAV["admin-phpmyadmin"]["label"] = "phpMyAdmin";
$NAV["admin-phpmyadmin"]["roll"] = "dev";
$NAV["admin-phpmyadmin"]["place"] = "admin";

$NAV["admin-backup"]["include"] = "admin/admin_backup.php";
$NAV["admin-backup"]["label"] = "Backup";
$NAV["admin-backup"]["roll"] = "admin";
$NAV["admin-backup"]["place"] = "admin";

?>