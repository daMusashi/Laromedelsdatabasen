<?php
// PHP DOM http://se2.php.net/manual/en/book.dom.php
// PHP XPATH (används av PHP DOM) http://www.w3schools.com/XPath/xpath_syntax.asp

/*
http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A1%3ApageLink%3A%3AILinkListener%3A%3A
http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A2%3ApageLink%3A%3AILinkListener%3A%3A
*/
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

?>
<h1>Bokimport</h1>
<?php
$url = "http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&facet_queries=&search_item_no=0&sort_advice=field%3DRelevance%26direction%3DDescending&arena_member_id=383116721&agency_name=ASE100112&search_type=solr&search_query=departmentId_index%3AASE100112%7C10004%7C10121%7C316";
//$url = "http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&facet_queries=&search_item_no=0&sort_advice=field%3DRelevance%26direction%3DDescending&arena_member_id=383116721&agency_name=ASE100112&search_type=solr&search_query=departmentId_index%3AASE100112%7C10004%7C10121%7C316&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A6%3ApageLink%3A%3AILinkListener%3A%3A";

/*
// använd CURL om nedan - file_get_contents inte är tillåtet
$html =  file_get_contents($url) or die("Kunde inte läsa webbsidan $url");
*/


$doc = new DOMDocument();
$doc->encoding = 'UTF-8'; // insert proper
$doc->substituteEntities = TRUE;
$doc->resolveExternals = true;
if($doc->loadHTMLFile($url)){
	//print $doc->saveHTML();	
	print $doc->saveHTML();	
} else {
	print "<p>Det gick dåligt</p>";	
}


?>